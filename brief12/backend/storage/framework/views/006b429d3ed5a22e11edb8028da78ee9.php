<?php $__env->startSection('title', 'Toutes les questions'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Questions Locales</h1>
        <a href="<?php echo e(route('questions.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i> Poser une question
        </a>
    </div>

    <!-- Search Box -->
    <div class="mb-8">
        <form action="<?php echo e(route('questions.index')); ?>" method="GET" class="relative">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm"
                placeholder="Rechercher une question...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <button type="submit" class="absolute inset-y-0 right-0 px-4 py-2 bg-gray-100 text-gray-600 rounded-r-lg hover:bg-gray-200 border-l border-gray-300">
                Chercher
            </button>
        </form>
    </div>

    <!-- Questions List -->
    <div class="space-y-6">
        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 text-indigo-500 font-bold">
                                    <?php echo e(strtoupper(substr($question->user->name ?? 'A', 0, 1))); ?>

                                </span>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-medium text-indigo-600 truncate">
                                    <a href="<?php echo e(route('questions.show', $question)); ?>" class="hover:underline">
                                        <?php echo e($question->title); ?>

                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    Par <?php echo e($question->user->name ?? 'Anonyme'); ?> • <?php echo e($question->created_at->diffForHumans()); ?>

                                    <?php if($question->location): ?>
                                        • <span class="text-gray-400"><i class="fas fa-map-marker-alt"></i> <?php echo e($question->location); ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-comment-alt mr-1"></i> <?php echo e($question->responses->count()); ?>

                            </div>
                            <?php if(auth()->guard()->check()): ?>
                                <form action="<?php echo e(route('likes.toggle', $question)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="flex items-center text-gray-500 hover:text-blue-600 transition <?php echo e(Auth::user()->hasLiked($question) ? 'text-blue-600' : ''); ?>">
                                        <i class="<?php echo e(Auth::user()->hasLiked($question) ? 'fas' : 'far'); ?> fa-thumbs-up mr-1"></i>
                                        <?php echo e($question->likes()->count()); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="flex items-center text-gray-400">
                                    <i class="far fa-thumbs-up mr-1"></i> <?php echo e($question->likes()->count()); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-600 line-clamp-2">
                            <?php echo e(Str::limit($question->content, 150)); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune question trouvée.</p>
                <a href="<?php echo e(route('questions.create')); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                    Soyez le premier à poser une question !
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-6">
        <?php echo e($questions->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/questions/index.blade.php ENDPATH**/ ?>