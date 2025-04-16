@extends('client.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[400px] sm:h-[450px] lg:h-[500px] bg-cover bg-center flex justify-center items-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset($page->featured_image ?? 'https://images.unsplash.com/photo-1617196701537-7329482cc9fe?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') }}')">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="z-10 text-center text-white max-w-3xl px-4 sm:px-6">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-brand mb-5 drop-shadow-lg">{{ $page->title }}</h1>
            <p class="text-lg mb-8 drop-shadow-md">{{ $page->meta_description }}</p>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <div class="py-16 bg-theme-primary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="prose prose-lg max-w-none prose-headings:text-theme-primary prose-p:text-theme-secondary">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    /* Additional styling for content from editor */
    .prose img {
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .prose h2 {
        color: #e61c23;
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        font-weight: bold;
        font-size: 2rem;
    }
    
    .prose h3 {
        color: #222;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: bold;
        font-size: 1.5rem;
    }
    
    [data-theme="dark"] .prose h3 {
        color: #fff;
    }
    
    .prose p {
        margin-bottom: 1.5rem;
    }
    
    .prose a {
        color: #e61c23;
        text-decoration: none;
    }
    
    .prose a:hover {
        text-decoration: underline;
    }
    
    /* Timeline styles */
    .timeline-container {
        position: relative;
    }
    
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        height: 100%;
        width: 2px;
        background-color: var(--accent-primary);
        transform: translateX(-50%);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 60px;
    }
    
    .timeline-dot {
        position: absolute;
        left: 50%;
        width: 20px;
        height: 20px;
        background-color: var(--accent-primary);
        border-radius: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    
    .timeline-content {
        position: relative;
        width: calc(50% - 30px);
        background-color: var(--card-bg);
        border-radius: 0.5rem;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .timeline-left {
        left: 0;
    }
    
    .timeline-right {
        left: calc(50% + 30px);
    }
    
    .timeline-left::after, .timeline-right::after {
        content: '';
        position: absolute;
        top: 10px;
        width: 30px;
        height: 2px;
        background-color: var(--accent-primary);
    }
    
    .timeline-left::after {
        right: -30px;
    }
    
    .timeline-right::after {
        left: -30px;
    }
    
    @media (max-width: 768px) {
        .timeline-container::before {
            left: 30px;
        }
        
        .timeline-dot {
            left: 30px;
        }
        
        .timeline-content {
            width: calc(100% - 80px);
            margin-left: 80px;
        }
        
        .timeline-left, .timeline-right {
            left: 0;
        }
        
        .timeline-left::after, .timeline-right::after {
            left: -30px;
        }
    }
</style>
@endpush