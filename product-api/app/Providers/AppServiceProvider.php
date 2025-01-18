<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\IProductGalleryRepository::class, \App\Repositories\Impls\ProductGalleryRepositoryImpl::class);
        $this->app->bind(\App\Repositories\IProductVideoRepository::class, \App\Repositories\Impls\ProductVideoRepositoryImpl::class);
        $this->app->bind(\App\Repositories\IProductCategoryRepository::class, \App\Repositories\Impls\ProductCategoryRepositoryImpl::class);
        $this->app->bind(\App\Repositories\ILogRepository::class, \App\Repositories\Impls\LogRepositoryImpl::class);
        $this->app->bind(\App\Repositories\ITagReferRepository::class, \App\Repositories\Impls\TagReferRepositoryImpl::class);
        $this->app->bind(\App\Repositories\IProductRepository::class, \App\Repositories\Impls\ProductRepositoryImpl::class);


        $this->app->bind(\App\Services\IProductGalleryService::class, \App\Services\Impls\ProductGalleryServiceImpl::class);
        $this->app->bind(\App\Services\IProductCategoryService::class, \App\Services\Impls\ProductCategoryServiceImpl::class);
        $this->app->bind(\App\Services\IProductVideoService::class, \App\Services\Impls\ProductVideoServiceImpl::class);
        $this->app->bind(\App\Services\ILogService::class, \App\Services\Impls\LogServiceImpl::class);
        $this->app->bind(\App\Services\IProductValidatorService::class, \App\Services\Impls\ProductValidatorServiceImpl::class);
        $this->app->bind(\App\Services\ITagReferService::class, \App\Services\Impls\TagReferServiceImpl::class);
        $this->app->bind(\App\Services\IProductService::class, \App\Services\Impls\ProductServiceImpl::class);
        $this->app->bind(\App\Services\IProductSkuService::class, \App\Services\Impls\ProductSkuServiceImpl::class);
        $this->app->bind(\App\Services\IVariantService::class, \App\Services\Impls\VariantServiceImpl::class);
        $this->app->bind(\App\Services\IProductTemplateService::class, \App\Services\Impls\ProductTemplateServiceImpl::class);

        $this->app->bind(\App\Queries\IProductQuery::class, \App\Queries\Impls\ProductQueryImpl::class);
        $this->app->bind(\App\Queries\ITemplateQuery::class, \App\Queries\Impls\TemplateQueryImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
