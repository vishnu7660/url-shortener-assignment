<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $invitations = Invitation::with(['company', 'inviter', 'role'])
                ->whereNull('accepted_at')
                ->latest()
                ->paginate(15);
        } else {
            $invitations = Invitation::with(['inviter', 'role'])
                ->where('company_id', $user->company_id)
                ->whereNull('accepted_at')
                ->latest()
                ->paginate(15);
        }

        return view('invitations.index', compact('invitations'));
    }

    public function create()
    {
        if (!auth()->user()->canInviteUsers()) {
            abort(403, 'You are not authorized to invite users.');
        }

        $user = auth()->user();
        $roles = [];
        $companies = [];

        if ($user->isSuperAdmin()) {
            // SuperAdmin can invite all roles
            $roles = Role::whereIn('name', [
                Role::ADMIN,
                Role::MEMBER,
                Role::SALES,
                Role::MANAGER
            ])->get();

            $companies = Company::all();
        } elseif ($user->isAdmin()) {
            // Admin can invite Sales & Manager only (based on assignment restriction)
            $roles = Role::whereIn('name', [
                Role::MEMBER,
                Role::ADMIN,
                Role::SALES,
                Role::MANAGER
            ])->get();
        }

        return view('invitations.create', compact('roles', 'companies'));
    }


    public function store(Request $request)
    {
        if (!auth()->user()->canInviteUsers()) {
            abort(403, 'You are not authorized to invite users.');
        }

        $user = auth()->user();

        $rules = [
            'email' => 'required|email|unique:invitations,email',
            'role_id' => 'required|exists:roles,id',
        ];

        if ($user->isSuperAdmin()) {
            $rules['company_action'] = 'required|in:existing,new';
            $rules['company_id'] = 'nullable|required_if:company_action,existing|exists:companies,id';
            $rules['company_name'] = 'nullable|required_if:company_action,new|string|max:255';

        }

        $validated = $request->validate($rules);

        $role = Role::find($validated['role_id']);

        // if ($user->isSuperAdmin() && $request->company_action === 'new' && $role->name === 'Admin') {
        //     return back()->withErrors([
        //         'role_id' => 'SuperAdmin cannot invite an Admin for a new company.'
        //     ])->withInput();
        // }

        if ($user->isSuperAdmin()) {
            if ($request->company_action === 'new') {
                $company = Company::create([
                    'name' => $request->company_name,
                    'slug' => Str::slug($request->company_name),
                ]);
                $companyId = $company->id;
            } else {
                $companyId = $request->company_id;
            }
        } else {
            $companyId = $user->company_id;
        }

        Invitation::create([
            'company_id' => $companyId,
            'invited_by' => $user->id,
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'token' => Str::random(32),
        ]);

        return redirect()
            ->route('invitations.index')
            ->with('success', 'Invitation sent successfully!');
    }



    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }

    public function acceptStore(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $invitation->email,
            'password' => Hash::make($validated['password']),
            'company_id' => $invitation->company_id,
            'role_id' => $invitation->role_id,
        ]);

        // Mark invitation as accepted
        $invitation->update([
            'accepted_at' => now(),
        ]);

        // Log in the user
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }
}
