<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Shop;
use Hash, Exception;

class AccountController extends Controller
{
	

	public function index()
	{

		$view_data['shops'] = Shop::with(['serviceProviders' => function($query){
			$query->where('activate', true);
			$query->orderBy('name', 'asc');
		}])->get();
		$view_data['counter_accounts'] = Account::where('level', 2)->get();
		$view_data['worker_accounts'] = Account::where('level', 3)
										->with(['service_provider' => function ($query) {
										    $query->with('Shop');
										}])->get();
		return view('admin.account.index', $view_data);
	}

	public function update_password(Request $request)
	{
		try{
			$password = $request->password1;
			if($request->password1 != $request ->password2){
				return back()->withErrors(['fail'=>'兩次密碼不一致']);
			}
			$account = Account::where('account', 'admin')->first();
			$account->password = Hash::make($request->password1);
			$account->save();
			return back();
		}
		catch(Exception $e){
			return back()->withErrors(['fail'=>'伺服器錯誤, 請洽系統商!']);
		}
		catch(\Illuminate\Database\QueryException $e){
			return back()->withErrors(['fail'=>'資料庫錯誤, 請洽系統商!']);
		}
	}

	public function api_add(Request $request)
	{
		try{
			if(is_null(Account::where('account', $request->account)->first())){
				$account = new Account;
				$account->account = $request->account;
				$account->password	 = Hash::make($request->password);
				$account->information = $request->information;
				$account->level = 2;
				$account->save();
				return response()->json('新增成功');
			}
			else{
				throw new Exception("此帳號已存在");
			}
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_worker_add(Request $request)
	{
		try{
			if(is_null(Account::where('account', $request->account)->first())){
				$account = new Account;
				$account->account = $request->account;
				$account->password	 = Hash::make($request->password);
				$account->level = 3;
				$account->service_provider_id = $request->worker_id;
				$account->save();
				return response()->json('新增成功');
			}
			else{
				throw new Exception("此帳號已存在");
			}
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_delete(Request $request)
	{
		try{
			$account = Account::where('id', $request->id)->whereIn('level', [2,3])->first();
			if(!is_null($account)){
				$account->delete();
				return response()->json('刪除成功');
			}
			else{
				throw new Exception("此帳號不存在");
			}
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_reset_password(Request $request)
	{
		try{
			$account = Account::where('id', $request->id)->whereIn('level', [2,3])->first();
			if(!is_null($account)){
				$account->password = Hash::make($request->password);
				$account->save();
				return response()->json('更改成功');
			}
			else{
				throw new Exception("此帳號不存在");
			}
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}

	public function api_reset_information(Request $request)
	{
		try{
			$account = Account::where('id', $request->id)->whereIn('level', [2,3])->first();
			if(!is_null($account)){
				$account->information = $request->information;
				$account->save();
				return response()->json('更改成功');
			}
			else{
				throw new Exception("此帳號不存在");
			}
		}
		catch(Exception $e){
			return response()->json($e->getMessage(), 400);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json('資料庫錯誤, 請洽系統商!', 400);
		}
	}
}