@extends('client.layouts.app')

@section('content')
    <!-- Tiêu đề trang -->
    <section class="py-12 bg-theme-primary border-b border-theme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-theme-primary mb-4">{{ trans_db('sections', 'contact_title', false) ?: 'Contact Us' }}</h1>
            <p class="text-xl text-theme-secondary max-w-3xl mx-auto">{{ trans_db('sections', 'contact_subtitle', false) ?: 'We would love to hear from you' }}</p>
        </div>
    </section>

    <!-- Thông tin liên hệ và Form đặt bàn -->
    <section class="py-12 bg-theme-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cột trái - Thông tin liên hệ -->
                <div class="w-full lg:w-1/3">
                    <div class="card rounded-lg shadow-md overflow-hidden h-full">
                        <div class="p-4 bg-theme-secondary border-b border-theme">
                            <h2 class="text-lg font-semibold text-theme-primary">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ trans_db('sections', 'contact_info_section', false) ?: 'Contact Information' }}
                            </h2>
                        </div>
                        <div class="p-6 flex flex-col h-full">
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-full bg-aisuki-red bg-opacity-10 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-map-marker-alt text-aisuki-red"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-medium text-theme-primary mb-1">{{ trans_db('sections', 'quick_contact_address_title', false) ?: 'Address' }}</h3>
                                        <p class="text-theme-secondary">{{ $currentLocale == 'en' ? setting('address') : trans_db('settings', 'address', false) }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-full bg-aisuki-red bg-opacity-10 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-phone-alt text-aisuki-red"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-medium text-theme-primary mb-1">{{ trans_db('sections', 'call_hotline', false) ?: 'Call Hotline' }}</h3>
                                        <p class="text-theme-secondary">{{ setting('phone') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-full bg-aisuki-red bg-opacity-10 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-envelope text-aisuki-red"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-medium text-theme-primary mb-1">{{ trans_db('sections', 'email', false) ?: 'Email' }}</h3>
                                        <p class="text-theme-secondary">{{ setting('email') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-full bg-aisuki-red bg-opacity-10 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-clock text-aisuki-red"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-medium text-theme-primary mb-1">{{ trans_db('sections', 'business_hours', false) ?: 'Business Hours' }}</h3>
                                        <p class="text-theme-secondary">{!! nl2br(str_replace(["\\r\\n", "\\n"], "<br />", $currentLocale == 'en' ? setting('opening_hours') : trans_db('settings', 'opening_hours', false))) !!}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8">
                                <h3 class="font-medium text-theme-primary mb-3">{{ trans_db('sections', 'follow_us', false) ?: 'Follow Us' }}</h3>
                                <div class="flex gap-3">
                                    @if(setting('facebook'))
                                        <a href="{{ setting('facebook') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    @endif
                                    
                                    @if(setting('instagram'))
                                        <a href="{{ setting('instagram') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    
                                    @if(setting('twitter'))
                                        <a href="{{ setting('twitter') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    @endif
                                    
                                    @if(setting('youtube'))
                                        <a href="{{ setting('youtube') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải - Form đặt bàn -->
                <div class="w-full lg:w-2/3">
                    <div class="card rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 bg-theme-secondary border-b border-theme">
                            <h2 class="text-lg font-semibold text-theme-primary">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ trans_db('sections', 'make_reservation_contact', false) ?: 'Make a Reservation' }}
                            </h2>
                        </div>
                        <div class="p-6">
                            @if(session('success'))
                                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                                    <p>{{ session('success') }}</p>
                                </div>
                            @endif
                        
                            <form action="{{ route('reservation.submit', ['locale' => app()->getLocale()]) }}" method="POST" id="reservationForm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Tên đầy đủ -->
                                    <div>
                                        <label for="name" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'reservation_form_name', false) ?: 'Full Name' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red @error('name') border-red-500 @enderror" placeholder="{{ trans_db('sections', 'reservation_form_name_placeholder', false) ?: 'Enter your full name' }}" value="{{ old('name') }}">
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Số điện thoại -->
                                    <div>
                                        <label for="phone" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'reservation_form_phone', false) ?: 'Phone Number' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <input type="tel" id="phone" name="phone" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red @error('phone') border-red-500 @enderror" placeholder="{{ trans_db('sections', 'reservation_form_phone_placeholder', false) ?: 'Enter your phone number' }}" value="{{ old('phone') }}">
                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Email -->
                                <div class="mb-6">
                                    <label for="email" class="block text-theme-primary font-medium mb-1">
                                        {{ trans_db('sections', 'reservation_form_email', false) ?: 'Email' }} <span class="text-aisuki-red">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red @error('email') border-red-500 @enderror" placeholder="{{ trans_db('sections', 'reservation_form_email_placeholder', false) ?: 'Enter your email' }}" value="{{ old('email') }}">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <!-- Ngày đặt -->
                                    <div>
                                        <label for="date" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'reservation_form_date', false) ?: 'Date' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <input type="date" id="date" name="date" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red @error('date') border-red-500 @enderror" value="{{ old('date', date('Y-m-d')) }}">
                                        @error('date')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Thời gian -->
                                    <div>
                                        <label for="time" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'reservation_form_time', false) ?: 'Time' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <select id="time" name="time" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red @error('time') border-red-500 @enderror">
                                            <option value="">{{ trans_db('sections', 'select_time', false) ?: 'Select time' }}</option>
                                            <option value="12:00" {{ old('time') == '12:00' ? 'selected' : '' }}>12:00</option>
                                            <option value="12:30" {{ old('time') == '12:30' ? 'selected' : '' }}>12:30</option>
                                            <option value="13:00" {{ old('time') == '13:00' ? 'selected' : '' }}>13:00</option>
                                            <option value="13:30" {{ old('time') == '13:30' ? 'selected' : '' }}>13:30</option>
                                            <option value="18:00" {{ old('time') == '18:00' ? 'selected' : '' }}>18:00</option>
                                            <option value="18:30" {{ old('time') == '18:30' ? 'selected' : '' }}>18:30</option>
                                            <option value="19:00" {{ old('time') == '19:00' ? 'selected' : '' }}>19:00</option>
                                            <option value="19:30" {{ old('time') == '19:30' ? 'selected' : '' }}>19:30</option>
                                            <option value="20:00" {{ old('time') == '20:00' ? 'selected' : '' }}>20:00</option>
                                        </select>
                                        @error('time')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Số lượng khách -->
                                    <div>
                                        <label for="guests" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'reservation_form_guests', false) ?: 'Number of Guests' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <select id="guests" name="guests" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red @error('guests') border-red-500 @enderror">
                                            <option value="">{{ trans_db('sections', 'select_guests', false) ?: 'Select guests' }}</option>
                                            <option value="2" {{ old('guests') == '2' ? 'selected' : '' }}>{{ trans_db('sections', 'reservation_form_guests_2', false) ?: '2 people' }}</option>
                                            <option value="3" {{ old('guests') == '3' ? 'selected' : '' }}>{{ trans_db('sections', 'reservation_form_guests_3', false) ?: '3 people' }}</option>
                                            <option value="4" {{ old('guests') == '4' ? 'selected' : '' }}>{{ trans_db('sections', 'reservation_form_guests_4', false) ?: '4 people' }}</option>
                                            <option value="5" {{ old('guests') == '5' ? 'selected' : '' }}>{{ trans_db('sections', 'reservation_form_guests_5', false) ?: '5 people' }}</option>
                                            <option value="6+" {{ old('guests') == '6+' ? 'selected' : '' }}>{{ trans_db('sections', 'reservation_form_guests_6plus', false) ?: '6 or more people' }}</option>
                                        </select>
                                        @error('guests')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Ghi chú đặt bàn -->
                                <div class="mb-6">
                                    <label for="notes" class="block text-theme-primary font-medium mb-1">
                                        {{ trans_db('sections', 'reservation_form_notes', false) ?: 'Notes' }}
                                    </label>
                                    <textarea id="notes" name="notes" rows="4" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red" placeholder="{{ trans_db('sections', 'reservation_form_notes_placeholder', false) ?: 'Enter your notes' }}">{{ old('notes') }}</textarea>
                                </div>
                                
                                <!-- Nút đặt bàn -->
                                <div class="flex justify-end">
                                    <button type="submit" id="reservationSubmitBtn" class="bg-aisuki-red text-white py-3 px-8 rounded-md hover:bg-aisuki-red-dark transition-colors flex items-center justify-center">
                                        <span class="normal-state">
                                            {{ trans_db('sections', 'reservation_form_submit', false) ?: 'Confirm Reservation' }} <i class="fas fa-calendar-check ml-2"></i>
                                        </span>
                                        <span class="loading-state hidden">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> {{ trans_db('sections', 'processing', false) ?: 'Processing...' }}
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Maps -->
    <section class="pt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mb-8">
            <h2 class="text-2xl font-bold text-theme-primary text-center mb-10">
                <i class="fas fa-map-marker-alt text-aisuki-red mr-2"></i>
                {{ trans_db('sections', 'find_us', false) ?: 'Find Us' }}
            </h2>
        </div>
        
        <div class="google-maps w-full h-96">
            {!! setting('google_maps_iframe') !!}
        </div>
    </section>

    <!-- Toast Notifications Container -->
    <div class="toast-container"></div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        $('#date').attr('min', today);

        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('border-red-500 focus\:border-aisuki-red');
            $(this).next('.validation-error').remove();
        });

        // Check for success message on page load and show toast if present
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        // Setup form submission with loading state
        setupFormSubmit();
        
        function setupFormSubmit() {
            // Track submission state
            let isSubmitting = false;
            
            // Get submit button
            const submitBtn = $('#reservationSubmitBtn');
            
            // Form validation and submission
            $('#reservationForm').on('submit', function(e) {
                // Prevent double submission
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }
                
                // Remove existing error messages
                $('.validation-error').remove();
                
                // Validate form fields
                if (!validateFormFields()) {
                    e.preventDefault();
                    
                    // Show general error message
                    showToast("{{ trans_db('validation', 'form_errors', false) ?: 'Please fix the errors in the form' }}", 'error');
                    return false;
                }
                
                // Set submitting state
                isSubmitting = true;
                
                // Show loading state and disable button
                submitBtn.prop('disabled', true);
                submitBtn.find('.normal-state').addClass('hidden');
                submitBtn.find('.loading-state').removeClass('hidden');
                
                // Let the form submit naturally
                return true;
            });
            
            // Reset form state when using back button
            $(window).on('pageshow', function(event) {
                if (event.originalEvent.persisted) {
                    // Page was loaded from cache (back button)
                    isSubmitting = false;
                    submitBtn.prop('disabled', false);
                    submitBtn.find('.normal-state').removeClass('hidden');
                    submitBtn.find('.loading-state').addClass('hidden');
                    $('.validation-error').remove();
                }
            });
        }
        
        // Form field validation
        function validateFormFields() {
            let isValid = true;
            
            // Reset all error states
            $('.validation-error').remove();
            $('.border-red-500').removeClass('border-red-500');
            
            // Define validation rules for each field
            const fields = [
                { 
                    id: 'name', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'name_required', false) ?: 'Name is required' }}"
                    }
                },
                { 
                    id: 'email', 
                    rules: ['required', 'email'], 
                    messages: {
                        required: "{{ trans_db('validation', 'email_required', false) ?: 'Email is required' }}",
                        email: "{{ trans_db('validation', 'email_invalid', false) ?: 'Please enter a valid email address' }}"
                    }
                },
                { 
                    id: 'phone', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'phone_required', false) ?: 'Phone number is required' }}"
                    }
                },
                { 
                    id: 'date', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'date_required', false) ?: 'Date is required' }}"
                    }
                },
                { 
                    id: 'time', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'time_required', false) ?: 'Time is required' }}"
                    }
                },
                { 
                    id: 'guests', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'guests_required', false) ?: 'Please select number of guests' }}"
                    }
                }
            ];
            
            // Validate each field
            fields.forEach(field => {
                const $field = $('#' + field.id);
                let fieldErrors = [];
                
                // Check each validation rule
                field.rules.forEach(rule => {
                    const value = $field.val().trim();
                    
                    if (rule === 'required' && value === '') {
                        fieldErrors.push(field.messages.required);
                    } else if (rule === 'email' && value !== '') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            fieldErrors.push(field.messages.email);
                        }
                    }
                });
                
                // If field has errors, mark it and show messages
                if (fieldErrors.length > 0) {
                    $field.addClass('border-red-500');
                    
                    // Add error message after the field
                    const errorContainer = $('<div class="validation-error text-red-500 text-xs mt-1"></div>');
                    errorContainer.text(fieldErrors[0]); // Show only the first error for each field
                    
                    // Find the field's parent and append error
                    $field.parent().append(errorContainer);
                    
                    isValid = false;
                }
            });
            
            return isValid;
        }

        
        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = $('.toast-container');
            const toastId = 'toast-' + Date.now();
            
            const toast = $(`
                <div id="${toastId}" class="toast toast-${type}">
                    <div class="toast-content">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} toast-icon"></i>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close"><i class="fas fa-times"></i></button>
                    <div class="toast-progress"></div>
                </div>
            `);
            
            toastContainer.append(toast);
            
            setTimeout(() => {
                toast.addClass('show');
            }, 100);
            
            setTimeout(() => {
                toast.removeClass('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000); // Show toast for 5 seconds instead of 3
            
            toast.find('.toast-close').on('click', function() {
                toast.removeClass('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
        }
    });
</script>
@endpush
@push('styles')
<style>
    #reservationSubmitBtn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .loading-state .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    @keyframes fa-spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
@endpush