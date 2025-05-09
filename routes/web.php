     <?php

     // use App\Http\Controllers\UserController as AdminUserController;
     use Illuminate\Support\Facades\Route;
     use App\Http\Controllers\AdminController;
     use App\Http\Controllers\CashierController;
     use App\Http\Controllers\CategoryController;
     use App\Http\Controllers\ProductController;
     use App\Http\Controllers\Auth\LoginController;
     use App\Http\Controllers\HomeController;
     use App\Http\Controllers\UserController;
     use App\Http\Controllers\TransactionController;



     /*
     |--------------------------------------------------------------------------
     | Public Routes
     |--------------------------------------------------------------------------
     */
     Route::get('/', [HomeController::class, 'index'])->name('home');

     /*
     |--------------------------------------------------------------------------
     | Auth Routes
     |--------------------------------------------------------------------------
     */
     Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
     Route::post('/login', [LoginController::class, 'login']);
     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

     /*
     |--------------------------------------------------------------------------
     | Admin Routes (hanya untuk role = admin)
     |--------------------------------------------------------------------------
     */
     Route::middleware(['auth', 'checkrole:admin'])
          ->prefix('admin')
          ->name('admin.')
          ->group(function() {
          // Dashboard admin
          Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

          // Register Kasir
          Route::get('register-cashier', [AdminController::class, 'showRegisterForm'])
               ->name('register.cashier');
          Route::post('register-cashier', [AdminController::class, 'registerCashier']);
          // BENAR
          Route::resource('users', UserController::class)
               ->except(['show','create']);


          // Reset Password Kasir
          //  Route::get('reset-password/{user}', [AdminController::class, 'showResetPasswordForm'])
          //       ->name('reset.password');
          //  Route::post('reset-password/{user}', [AdminController::class, 'resetPassword']);
     });

     /*
     |--------------------------------------------------------------------------
     | Cashier Routes (hanya untuk role = cashier)
     |--------------------------------------------------------------------------
     */
     Route::middleware(['auth', 'checkrole:cashier'])
          ->prefix('cashier')
          ->name('cashier.')
          ->group(function() {
          Route::get('dashboard', [CashierController::class, 'dashboard'])
               ->name('dashboard');
     });

     /*
     |--------------------------------------------------------------------------
     | Resource Routes (hanya untuk admin)
     |--------------------------------------------------------------------------
     */
     Route::middleware(['auth', 'checkrole:admin'])
          ->group(function() {
          Route::resource('products', ProductController::class);
          Route::resource('categories', CategoryController::class);
     });

     Route::middleware(['auth','checkrole:cashier'])
          ->prefix('cashier')
          ->name('cashier.')
          ->group(function() {
          // Form Buat Transaksi
          Route::get('transactions/create', [TransactionController::class,'create'])
               ->name('transactions.create');

          // Simpan Transaksi
          Route::post('transactions', [TransactionController::class,'store'])
               ->name('transactions.store');

               Route::get('transactions', [TransactionController::class, 'index'])
               ->name('transactions.index');

         // web.php, di dalam group cashier…
          Route::get('transactions/{sale}/invoice', [TransactionController::class,'invoice'])
          ->name('transactions.show');   // => cashier.transactions.show

                    
     });

     