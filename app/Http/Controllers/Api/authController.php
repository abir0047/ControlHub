<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactUs;
use App\Mail\VerifyEmail;
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
			// 'password' => 'confirmed | required ',

		]);
		$password = Str::random(10);

		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($password),
		]);

		DB::table('exam_access')->insert([
			'examinee' => $user->id,
			'exam_group_id' => 1,
		]);
		$sendMail = $data['name'];

		Mail::to($sendMail)->send(new VerifyEmail($sendMail, $password));


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

		if ($user->email_verified_at == null) {
			User::where('id', $user->id)->update([
				'email_verified_at' => date("Y-m-d H:i:s", time()),
			]);
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
		$user = User::where('email', $data['email'])->first();
		DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

		$response = [
			'message' => "Successfully logged out",
		];
		return response()->json($response, 201);
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
		User::where('id', $user->id)->update([
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
		User::where('id', $user->id)->update([
			'password' => Hash::make($newPassword),
		]);

		Mail::to($sendMail)->send(new PasswordReset($sendMail, $newPassword));

		$response = [
			'message' => "Password resent mail has been sent successfully",
			'user' => $user,
		];

		return response($response, 201);
	}

	public function googleSignIn(Request $request)
	{
		if ($request->displayName == null || $request->email == null) {
			return response([
				'message' => "The api request is not google sign-in",
			], 401);
		}

		$user = User::where('email', $request->email)->first();

		if (!$user) {
			$user = User::create([
				'name' => $request->displayName,
				'email' => $request->email,
				'password' => bcrypt($request->serverAuthCode),
				'email_verified_at' => date("Y-m-d H:i:s", time()),
			]);

			DB::table('exam_access')->insert([
				'examinee' => $user->id,
				'exam_group_id' => 1,
			]);
			$token = $user->createToken('adminControlToken')->plainTextToken;

			$response = [
				'user' => $user,
				'token' => $token,
			];

			return response($response, 201);
		} else {
			if ($user->email_verified_at == null) {
				User::where('id', $user->id)->update([
					'email_verified_at' => date("Y-m-d H:i:s", time()),
				]);
			}
			$token = $user->createToken('adminControlToken')->plainTextToken;

			$response = [
				'user' => $user,
				'token' => $token,
			];

			return response($response, 201);
		}
	}

	public function contactMail(Request $request)
	{
		request()->validate([
			'query' => 'required',
			'subject' => 'required',
			'email' => 'required'
		]);
		$query = $request->get('query');
		$userEmail = $request->get('email');
		$subject = $request->get('subject');
		Mail::to(config('mail.from.address'))->send(new ContactUs($userEmail, $subject, $query));

		$token = $request->bearerToken();
		$response = [
			'message' => "Your query has been sent",
			'token' => $token,
		];

		return response($response, 201);
	}
}
