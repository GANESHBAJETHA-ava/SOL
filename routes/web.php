<?php

// use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/home', function () {
//     return view('index');
// })->name('home');


Route::get('/', 'HomeController@index')->name('home');

Route::post('check-phone', 'UserAuthController@checkPhone');
Route::post('send-otp', 'UserAuthController@sendOtp');
Route::get('verify-otp/{phone}/{otp}', 'UserAuthController@verifyOtp');
Route::get('check-phone-number/{phone}', 'UserAuthController@checkPhoneNumber');
Route::post('change-password-after-top', 'UserAuthController@changePasswordAfterOtp');
Route::get('get-banner', 'AdminController@getBanner');

Route::get('download-apk', 'HomeController@downloadApk');

Route::group(['middleware' => ['guest']], function() {
    Route::get('login', 'UserAuthController@getLogin');
    Route::post('user-login', 'UserAuthController@login');
    Route::get('register', 'UserAuthController@getRegister');
    Route::post('sign-up', 'UserAuthController@signUp');
    Route::get('forgot-password', 'UserAuthController@forgotPassword');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('get-user', 'HomeController@getUser');
    Route::post('user-logout', 'UserAuthController@UserLogout');
    // GAME
    Route::get('get-game/{slug}', "GameController@getGame");
    Route::post('get-game-records/{slug}', "GameController@getGameRecords");
    Route::get('get-my-game-orders/{slug}', "GameController@getMyGameOrders");
    Route::post('submit-new-order', 'GameController@submitNewOrder');
    // GENERAL
    Route::get('get-my-levels', 'GeneralController@getMyLevels');
    //Bank
    Route::post('bank', 'BankController@Bank');
    Route::get('banks', 'BankController@Banks');
    Route::get('/bank/{id}/edit', 'BankController@editBank');
    Route::post('/bank/{id}', 'BankController@updateBank');
    Route::get('delete/bank/{id}', 'BankController@deleteBank');
    //Address
    Route::post('address', 'AddressController@Address');
    Route::get('addresses', 'AddressController@Addresses');
    Route::get('/address/{id}/edit', 'AddressController@editAddress');
    Route::post('/address/{id}', 'AddressController@updateAddress');
    Route::get('delete/address/{id}', 'AddressController@deleteAddress');
    //Complains
    Route::post('complain', 'ComplainController@Complain');
    Route::get('complains', 'ComplainController@Complains');
    // Recharge
    Route::post('recharge', 'RechargeController@Recharge');
    Route::get('recharges', 'RechargeController@Recharges');
    Route::post('utr', 'RechargeController@Utr');
    // Withdrawal
    Route::post('withdrawal', 'WithdrawalController@Withdrawal');
    Route::get('get-withdrawal-requests/{type}', 'WithdrawalController@getWithdrawalRequest');
});

Route::group(['middleware' => ['auth', 'is_admin']], function () {
    Route::post('upi', 'UpiController@Upi');
    Route::get('upis', 'UpiController@upis');
    Route::post('change-upi-status', 'UpiController@changeUpiStatus');
    Route::get('delete/upi/{id}', 'UpiController@deleteUpi');
    Route::post('users', 'AdminController@Users');
    Route::post('user/block', 'AdminController@userBlock');
    Route::get('get-user/{user_id}', 'AdminController@getUser');
    Route::get('get-user-levels/{user_id}', 'AdminController@getUserLevels');
    Route::post('/change-user-password', 'AdminController@changeUserPassword');
    Route::post('update-home-banner', 'AdminController@updateHomeBanner');
    Route::post('update-promotion-banner', 'AdminController@updatePromotionBanner');
    Route::post('update-dw-banner', 'AdminController@updateDwBanner');


    // DW
    Route::post('get-deposit-data', 'DepositeWithdrawalController@GetDepositData');
    Route::post('/chanage-deposit-status', 'DepositeWithdrawalController@ChangeDepositStatus');
    Route::post('/deposit-rejected', 'DepositeWithdrawalController@DepositRejected');
    Route::post('get-withdrawal-data', 'DepositeWithdrawalController@GetWithdrawalData');
    Route::post('/chanage-withdrawal-status', 'DepositeWithdrawalController@ChangeWithdrawalStatus');
    Route::post('/withdrawal-rejected', 'DepositeWithdrawalController@WithdrawalRejected');
    // Reward Withdrawal
    Route::post('get-reward-withdrawal-data', 'RewardWithdrawalController@GetRewardWithdrawalData');
    Route::post('/chanage-reward-withdrawal-status', 'RewardWithdrawalController@ChangeRewardWithdrawalStatus');
    Route::post('/reward-withdrawal-rejected', 'RewardWithdrawalController@RewardWithdrawalRejected');
    // Game Mode
    Route::get('get-all-games', 'GameModeController@getAllGames');
    Route::get('get-game-current-period/{game_slug}', 'GameModeController@getGameCurrentPeriod');
    Route::post('update-period-mode', 'GameModeController@updatePeriodMode');
    Route::post('submit-game-result', 'GameModeController@submitGameResult');
    Route::get('get-old-periods/{game_slug}', 'GameModeController@getOldPeriods');
    Route::post('get-current-period-book', 'GameModeController@getCurrentPeriodBook');
    // Settings
    Route::get('get-settings', 'SettingsController@getSettings');
    Route::post('save-settings', 'SettingsController@saveSettings');
    // Dashboard
    Route::get('get-dashboard-data', 'DashboardController@getDashboardData');
    // DUMMY
    Route::get('get-dummy-wins-data', 'DummyController@getDummyWinsData');
    Route::post('add-dummy-wins', 'DummyController@addDummyWins');
    Route::post('update-dummy-wins-data', 'DummyController@updateDummyWins');
    Route::post('update-dummy-wins-status', 'DummyController@updateDummyWinsStatus');
    Route::post('delete-dummy-wins', 'DummyController@deleteDummyWinsStatus');
    // Complains
    Route::get('customer-complains', 'ComplainController@CustomerComplains');
    Route::post('complains-reply', 'ComplainController@ComplainReply');
    // Deposit
    Route::post('add-deposit-amount', 'AdminController@DepositAmount');
    // Withdrawal
    Route::post('withdrawal-amount', 'AdminController@WithdrawalAmount');
    // SPORTS
    Route::get('get-sports-data', 'SportController@getData');
    Route::post('add-sport', 'SportController@addSport');
    Route::post('update-sport-data', 'SportController@updateSport');
    Route::post('update-sports-status', 'SportController@updateSportsStatus');
    Route::post('delete-sport', 'SportController@deleteSport');
    // MATCHS
    Route::get('get-matchs-data', 'MatchController@getData');
    Route::post('add-match', 'MatchController@addMatch');
    Route::post('update-match-data', 'MatchController@updateMatch');
    Route::post('update-match-status', 'MatchController@updateMatchStatus');
    Route::post('update-dummy-wins-status', 'DummyController@updateDummyWinsStatus');
    Route::post('delete-match', 'MatchController@deleteMatch');
    // MARKETS
    Route::get('get-markets-data', 'MarketController@getData');
    Route::get('get-sport-matches-data/{sport_slug}', 'MarketController@getSportMatchesData');
    Route::post('add-market', 'MarketController@addMarket');
    Route::post('update-market-data', 'MarketController@updateMarket');
    Route::post('update-market-status', 'MarketController@updateMarketStatus');
    Route::post('delete-market', 'MarketController@deleteMarket');

});

Route::get('/{any}', 'HomeController@userIndex')->where('any', '.*');
