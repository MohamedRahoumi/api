<?php $__env->startSection('title', 'Mes questions favorites'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mes Questions Favorites</h1>
        <a href="<?php echo e(route('questions.index')); ?>" class="text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux questions
        </a>
    </div>

    <!-- Favorites List -->
    <div class="space-y-6">
        <?php $__empty_1 = true; $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favorite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <div class="shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100 text-yellow-600 font-bold">
                                    <?php echo e(strtoupper(substr($favorite->question->user->name ?? 'A', 0, 1))); ?>

                                </span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h2 class="text-lg font-medium text-indigo-600 truncate">
                                    <a href="<?php echo e(route('questions.show', $favorite->question)); ?>" class="hover:underline">
                                        <?php echo e($favorite->question->title); ?>

                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    Par <?php echo e($favorite->question->user->name ?? 'Anonyme'); ?> • <?php echo e($favorite->question->created_at->diffForHumans()); ?>

                                    <?php if($favorite->question->location): ?>
                                        • <span class="text-gray-400"><i class="fas fa-map-marker-alt"></i> <?php echo e($favorite->question->location); ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 ml-4">
                            <div class="flex items-center">
                                <i class="fas fa-comment-alt mr-1"></i> <?php echo e($favorite->question->responses->count()); ?>

                            </div>
                            <form action="<?php echo e(route('favorites.toggle', $favorite->question)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-yellow-500 hover:text-gray-500 transition" title="Retirer des favoris">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-600 line-clamp-2">
                            <?php echo e(Str::limit($favorite->question->content, 150)); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-star text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Vous n'avez pas encore de questions favorites.</p>
                <a href="<?php echo e(route('questions.index')); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                    Explorez les questions et ajoutez vos favorites !
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-6">
        <?php echo e($favorites->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/favorites/index.blade.php ENDPATH**/ ?>