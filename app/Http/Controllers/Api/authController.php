<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class authController extends Controller
{
	public function register(Request $request)
	{
		$data = $request->validate([
			'name' => 'required | string',
			'email' => 'required | unique:users',
			'password' => 'confirmed | required ',

		]);
		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);

		$group = DB::table('exam_groups')->where('name', 'Group Free')->first();

		if (!$group) {
			return response([
				'message' => "Name of the free group must be 'Group Free'. Contact Admin."
			], 401);
		}
		DB::table('exam_access')->insert([
			'examinee' => $user->id,
			'exam_group_id' => $group->id,
		]);

		$token = $user->createToken('adminControlToken')->plainTextToken;

		$response = [
			'user' => $user,
			'token' => $token,
		];

		return response($response, 201);
	}

	public function login(Request $request)
	{

		$data = $request->validate([
			'email' => 'required',
			'password' => 'required',

		]);

		$user = User::where('email', $data['email'])->first();

		if (!$user || !Hash::check($data['password'], $user->password)) {
			return response([
				'message' => "Email or password is incorrect"
			], 401);
		}

		$token = $user->createToken('adminControlToken')->plainTextToken;

		$response = [
			'user' => $user,
			'token' => $token,
		];

		return response($response, 201);
	}

	public function logout(Request $request)
	{
		$data = $request->validate([
			'email' => 'required',
		]);
		$user = User::where('email', $data['email'])->token_get_all()->first();
		DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

		return response()->json('Successfully logged out');
	}

	public function showProfile(Request $request)
	{
		$data = $request->validate([
			'email' => 'required',
		]);
		$user = User::where('email', $data['email'])->first();
		if (!$user) {
			return response([
				'message' => "Email not found"
			], 401);
		}
		$token = $request->bearerToken();
		$response = [
			'user' => $user,
			'token' => $token,
		];

		return response($response, 201);
	}
	public function updatePassword(Request $request)
	{
		$data = $request->validate([
			'email' => 'required',
			'oldPassword' => 'required',
			'newPassword' => 'confirmed | required ',
		]);
		$user = User::where('email', $data['email'])->first();
		if (!$user) {
			return response([
				'message' => "User does not exist.",
			], 401);
		} else if (!Hash::check($data['oldPassword'], $user->password)) {
			return response([
				'message' => "Password is incorrect."
			], 401);
		}
		$user = User::where('id', $user->id)->update([
			'password' => bcrypt($data['newPassword']),
		]);
		$token = $request->bearerToken();
		$response = [
			'message' => "Password is changed",
			'user' => $user,
			'token' => $token,
		];

		return response($response, 201);
	}
	public function updateInformation(Request $request)
	{
		$data = $request->validate([
			'name' => 'nullable |string',
			'email' => 'required',
			'mobile' => 'nullable | integer',
			'district' => 'nullable | string',
			'birth_date' => 'nullable | string',
			'university' => 'nullable | string',
			'gender' => 'nullable | string',

		]);
		$user = User::where('email', $data['email'])->first();
		if (!$user) {
			return response([
				'message' => "User does not exist.",
			], 401);
		}
		if (array_key_exists('name', $data)) {
			$user->name = $data['name'];
		}
		if (array_key_exists('mobile', $data)) {
			$user->mobile = $data['mobile'];
		}
		if (array_key_exists('district', $data)) {
			$user->district = $data['district'];
		}
		if (array_key_exists('birth_date', $data)) {
			$user->birth_date = $data['birth_date'];
		}
		if (array_key_exists('university', $data)) {
			$user->university = $data['university'];
		}
		if (array_key_exists('gender', $data)) {
			$user->gender = $data['gender'];
		}
		$user = User::where('id', $user->id)->update([
			'name' => $user->name,
			'mobile' => $user->mobile,
			'district' => $user->district,
			'birth_date' => $user->birth_date,
			'university' => $user->university,
			'gender' => $user->gender,
		]);
		$token = $request->bearerToken();
		$response = [
			'message' => "Profile is updated",
			'user' => $user,
			'token' => $token,
		];

		return response($response, 201);
	}

	public function passwordReset(Request $request)
	{
		$data = $request->validate([
			'email' => 'required',
		]);
		$user = User::where('email', $data['email'])->first();

		if (!$user) {
			return response([
				'message' => "User does not exist.",
			], 401);
		}

		$sendMail = $user->email;
		$newPassword = Str::random(10);
		$user = User::where('id', $user->id)->update([
			'password' => Hash::make($newPassword),
		]);

		Mail::to($sendMail)->send(new PasswordReset($sendMail, $newPassword));

		$response = [
			'message' => "Password resent mail has been sent successfully",
		];

		return response($response, 201);
	}
}
