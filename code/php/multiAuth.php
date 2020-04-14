<?php
// 1. Create Model, Migration, Controller for Admin.
// 2. Copy User model as Admin model.
// 3. Add Guards and Providers on auth.php for Admin.
// 4. Modify RedirectIfAuthenticated.php like that:

public function handle($request, Closure $next, $guard = null)
{
    // if (Auth::guard($guard)->check()) {
    //     return redirect('/home');
    // }
	
	switch ($guard) 
	{
		case 'admin':
		if (Auth::guard($guard)->check()) {
			return redirect()->route('admin.dashboard');
		}
		break;

		default:
		if (Auth::guard($guard)->check()) {
			return redirect('/home');
		}
		break;
	}
	return $next($request);
}

//5. Admin Login, Logout function should look like that:

public function login(Request $request)
{
	$this->validate($request, [
		'username'   => 'required',
		'password' => 'required'
	]);

	if (Auth::guard('admin')->attempt(['username' => $request->username, 
		'password' => $request->password])) 
	{
		return redirect()->intended(route('admin.dashboard'));
	} 
	return redirect()->back()->with('alert','Username and Password Not Matched');
}

public function logout(Request $request)
{
	Auth::guard('admin')->logout();
	$request->session()->invalidate();
	return redirect()->intended(route('admin.login'));
}

