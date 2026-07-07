<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\UserMLMController;
use App\Http\Controllers\User\FundSummaryController;
use App\Http\Controllers\User\FundRequestController;
use App\Http\Controllers\User\FundRequestStatusController;
use App\Http\Controllers\User\FundTransferController;
use App\Http\Controllers\User\FundHistoryController;
use App\Http\Controllers\User\WalletController;use App\Http\Controllers\User\DirectIncomeController;
use App\Http\Controllers\User\MatchingIncomeController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\CashBonusRequestController;
use App\Http\Controllers\User\ClaimCashRequestController;
use App\Http\Controllers\User\CashBonusHistoryController;
use App\Http\Controllers\User\GenerationIncomeController;
use App\Http\Controllers\User\AwardsRewardsController;
use App\Http\Controllers\User\DownlineRankController;
use App\Http\Controllers\User\WeeklyPayoutController;
use App\Http\Controllers\User\RetreatToursController;
use App\Http\Controllers\User\OrderHistoryController;
use App\Http\Controllers\User\ByHandDeliveryController;
use App\Http\Controllers\User\CourierDeliveryController;
use App\Http\Controllers\User\ByHandAwardController;
use App\Http\Controllers\User\ByCourierAwardController;
use App\Http\Controllers\User\OtherProductsController;
use App\Http\Controllers\User\GrievanceController;
use App\Http\Controllers\User\IncomeController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\OrderForSomeoneController;
use App\Http\Controllers\User\BankDetailController;
use App\Http\Controllers\User\CallbackController;

// ── Public Routes ─────────────────────────────────────────────────
Route::match(['get', 'post'], '/', [LoginController::class, 'handleLogin'])->name('login');

Route::get('/register', function () {
    return view('pages.auth.register');
})->name('register');

Route::get('/privacy-policy', function () {
    return redirect(route('register'));
})->name('privacy-policy');

Route::get('/terms-conditions', function () {
    return redirect(route('register'));
})->name('terms-conditions');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Public Product Browsing ───────────────────────────────────────
Route::get('/buy-now', [ProductController::class, 'buyNow'])->name('buy-now');

// ── Authenticated Routes ──────────────────────────────────────────
Route::middleware('auth.mlm')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/purchase', [ProductController::class, 'purchase'])->name('purchase');

    // Profile
    Route::get('/my-profile', [UserController::class, 'editProfile'])->name('user.profile');
    Route::post('/my-profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/profile-image', [UserController::class, 'editProfileImage'])->name('user.profile.image');
    Route::post('/profile-image/upload', [UserController::class, 'uploadProfileImage'])->name('user.profile.image.upload');
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change-password');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('user.change-password.update');
    Route::get('/user-registration', function () {
        return view('pages.user.registration');
    })->name('user.registration');
    Route::get('/welcome-letter', [UserController::class, 'welcomeLetter'])->name('user.welcome-letter');
    Route::get('/cards', [UserController::class, 'cards'])->name('user.cards');
    Route::get('/visiting-card', [UserController::class, 'visitingCard'])->name('user.visiting-card');
    Route::post('/visiting-card/download', [UserController::class, 'downloadVisitingCard'])->name('user.visiting-card.download');
    Route::get('/signup-acknowledgement', [UserController::class, 'signupAcknowledgement'])->name('user.signup-acknowledgement');

    // MLM / Team
    Route::get('/direct-business', [UserMLMController::class, 'directBusiness'])->name('user.direct-business');
    Route::get('/downline-business', [UserMLMController::class, 'downlineBusiness'])->name('user.downline-business');
    Route::get('/genealogy', [UserMLMController::class, 'genealogy'])->name('user.genealogy');
    Route::get('/pending-placement', [UserMLMController::class, 'pendingPlacement'])->name('user.pending-placement');
    Route::post('/pending-placement/place', [UserMLMController::class, 'pendingPlacementPlace'])->name('user.pending-placement.place');
    Route::get('/user-profile/{userId}/modal', [UserMLMController::class, 'getUserProfileModal'])->name('user.profile.modal');
    Route::get('/user-tree/{userId}/html', [UserMLMController::class, 'getUserTree'])->name('user.tree.html');
    Route::get('/user-referrals/{userId}', [UserMLMController::class, 'getReferralList'])->name('user.referral.list');

    // Bank / Fund
    Route::get('/wallet', [WalletController::class, 'index'])->name('user.wallet');
    Route::get('/fund-summary', [FundSummaryController::class, 'index'])->name('user.fund-summary');
    Route::get('/fund-request', [FundRequestController::class, 'index'])->name('user.fund-request');
    Route::get('/api/fund-request/bank-details', [FundRequestController::class, 'getBankDetails'])->name('user.fund-request.bank-details');
    Route::post('/fund-request/submit', [FundRequestController::class, 'submit'])->name('user.fund-request.submit');
    Route::get('/fund-request-status', [FundRequestStatusController::class, 'index'])->name('user.fund-request-status');
    Route::get('/fund-transfer', [FundTransferController::class, 'index'])->name('user.fund-transfer');
    Route::post('/fund-transfer/transfer', [FundTransferController::class, 'transfer'])->name('user.fund-transfer.transfer');
    Route::get('/fund-list', [FundTransferController::class, 'getSentTransfers'])->name('user.fund-list');
    Route::get('/fund-receive-list', [FundTransferController::class, 'getReceivedTransfers'])->name('user.fund-receive-list');
    Route::get('/api/wallet-balance', [FundTransferController::class, 'getWalletBalance'])->name('user.wallet-balance');
    Route::get('/fund-history', [FundHistoryController::class, 'index'])->name('user.fund-history');

    // Wallet
    Route::get('/wallet/transactions', [WalletController::class, 'getTransactions'])->name('user.wallet.transactions');
    Route::get('/account-summary', [WalletController::class, 'accountSummary'])->name('user.account.summary');
    Route::get('/account-summary/data', [WalletController::class, 'getAccountSummaryData'])->name('user.account.summary.data');

    // Income
    Route::get('/direct-income', [DirectIncomeController::class, 'index'])->name('user.direct-income');
    Route::get('/direct-income/data', [DirectIncomeController::class, 'getDirectIncomeData'])->name('user.direct-income.data');
    Route::get('/matching-income', [MatchingIncomeController::class, 'index'])->name('user.matching-income');
    Route::get('/matching-income/data', [MatchingIncomeController::class, 'getMatchingIncomeData'])->name('user.matching-income.data');
    Route::get('/income-log', [IncomeController::class, 'index'])->name('user.income-log');

    // Cash Bonus
    Route::get('/cash-bonus-request', [CashBonusRequestController::class, 'index'])->name('user.cash-bonus-request');
    Route::get('/cash-bonus-request/data', [CashBonusRequestController::class, 'getCashBonusData'])->name('user.cash-bonus-request.data');
    Route::get('/claim-cash-request', [ClaimCashRequestController::class, 'index'])->name('user.claim-cash-request');
    Route::get('/claim-cash-request/data', [ClaimCashRequestController::class, 'getClaimData'])->name('user.claim-cash-request.data');
    Route::get('/cash-bonus-history', [CashBonusHistoryController::class, 'index'])->name('user.cash-bonus-history');
    Route::get('/cash-bonus-history/data', [CashBonusHistoryController::class, 'getHistoryData'])->name('user.cash-bonus-history.data');

    // Generation / Awards
    Route::get('/generation-income', [GenerationIncomeController::class, 'index'])->name('user.generation-income');
    Route::get('/generation-income/data', [GenerationIncomeController::class, 'getGenerationIncomeData'])->name('user.generation-income.data');
    Route::get('/awards-rewards', [AwardsRewardsController::class, 'index'])->name('user.awards-rewards');
    Route::get('/awards-rewards/data', [AwardsRewardsController::class, 'getAwardsData'])->name('user.awards-rewards.data');

    // Downline / Payout / Tours
    Route::get('/downline-rank', [DownlineRankController::class, 'index'])->name('user.downline-rank');
    Route::get('/downline-rank/data', [DownlineRankController::class, 'getDownlineRankData'])->name('user.downline-rank.data');
    Route::get('/weekly-payout', [WeeklyPayoutController::class, 'index'])->name('user.weekly-payout');
    Route::get('/weekly-payout/data', [WeeklyPayoutController::class, 'getPayoutData'])->name('user.weekly-payout.data');
    Route::get('/retreat-tours', [RetreatToursController::class, 'index'])->name('user.retreat-tours');
    Route::get('/retreat-tours/data', [RetreatToursController::class, 'getToursData'])->name('user.retreat-tours.data');

    // Orders
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('user.order-history');
    Route::get('/order-for-someone', [OrderForSomeoneController::class, 'index'])->name('user.order-for-someone');
    Route::post('/order-for-someone/place', [OrderForSomeoneController::class, 'placeOrder'])->name('user.order-for-someone.place');

    // Invoice Download
    Route::get('/invoice/{publicId}/download', function ($publicId) {
        $apiBaseUrl = env('API_BASE_URL');
        $response = Http::timeout(30)->get("{$apiBaseUrl}/invoice/{$publicId}/download");
        if ($response->successful()) {
            return response($response->body(), 200, [
                'Content-Type' => $response->header('Content-Type') ?: 'application/pdf',
                'Content-Disposition' => $response->header('Content-Disposition') ?: 'attachment; filename="invoice.pdf"',
            ]);
        }
        return back()->with('error', 'Invoice not found.');
    })->name('invoice.download');

    // Delivery
    Route::get('/by-hand-delivery', [ByHandDeliveryController::class, 'index'])->name('user.by-hand-delivery');
    Route::get('/by-hand-delivery/data', [ByHandDeliveryController::class, 'getDeliveryData'])->name('user.by-hand-delivery.data');
    Route::get('/courier-delivery', [CourierDeliveryController::class, 'index'])->name('user.courier-delivery');
    Route::get('/courier-delivery/data', [CourierDeliveryController::class, 'getCourierData'])->name('user.courier-delivery.data');
    Route::get('/by-hand-award', [ByHandAwardController::class, 'index'])->name('user.by-hand-award');
    Route::get('/by-hand-award/data', [ByHandAwardController::class, 'getAwardData'])->name('user.by-hand-award.data');
    Route::get('/by-courier-award', [ByCourierAwardController::class, 'index'])->name('user.by-courier-award');
    Route::get('/by-courier-award/data', [ByCourierAwardController::class, 'getCourierAwardData'])->name('user.by-courier-award.data');
    Route::get('/other-products', [OtherProductsController::class, 'index'])->name('user.other-products');
    Route::get('/other-products/data', [OtherProductsController::class, 'getOtherProductsData'])->name('user.other-products.data');

    // Grievance Cell
    Route::get('/grievance/raise-ticket', [GrievanceController::class, 'raiseTicket'])->name('user.grievance.raise-ticket');
    Route::post('/grievance/submit', [GrievanceController::class, 'submitTicket'])->name('user.grievance.submit');
    Route::get('/grievance/outbox', [GrievanceController::class, 'outbox'])->name('user.grievance.outbox');
    Route::get('/grievance/outbox/data', [GrievanceController::class, 'getOutboxData'])->name('user.grievance.outbox.data');
    Route::get('/grievance/ticket-messages/{id}', [GrievanceController::class, 'getTicketMessages'])->name('user.grievance.ticket-messages');
    Route::post('/grievance/reply-ticket', [GrievanceController::class, 'replyTicket'])->name('user.grievance.reply-ticket');
    Route::get('/grievance/inbox', [GrievanceController::class, 'inbox'])->name('user.grievance.inbox');
    Route::get('/grievance/inbox/data', [GrievanceController::class, 'getInboxData'])->name('user.grievance.inbox.data');

    // KYC
    Route::get('/kyc', [KycController::class, 'index'])->name('user.kyc');
    Route::post('/kyc/submit', [KycController::class, 'submit'])->name('user.kyc.submit');

    // Withdrawal
    Route::get('/withdrawal-history', [FundHistoryController::class, 'withdrawalHistory'])->name('withdrawal.history');

    // Bank Details (User's own bank account for receiving payouts)
    Route::get('/bank-detail', [BankDetailController::class, 'show'])->name('user.bank-detail.show');
    Route::post('/bank-detail/save', [BankDetailController::class, 'save'])->name('user.bank-detail.save');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('user.notifications');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('user.notifications.unread-count');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('user.notifications.mark-read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('user.notifications.mark-all-read');

    // Callback Scheduling
    Route::get('/schedule-callback', [CallbackController::class, 'index'])->name('user.callback');
    Route::post('/schedule-callback/submit', [CallbackController::class, 'submit'])->name('user.callback.submit');

});
