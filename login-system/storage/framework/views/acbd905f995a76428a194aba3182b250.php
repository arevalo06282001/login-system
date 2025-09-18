

<?php $__env->startSection('title', 'Register - Secure System'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="col-md-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4>Create Account</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="<?php echo e(route('login')); ?>">Already have an account? Sign in</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Aaron Pogi\Documents\main\login-system\resources\views/auth/register.blade.php ENDPATH**/ ?>