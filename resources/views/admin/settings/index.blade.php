@extends('admin.layouts.app')

@section('title', 'Website Settings')

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card settings-nav-card sticky-top" style="top: 80px;">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush settings-nav" id="settings-nav">
                            <a href="#general-settings" class="list-group-item list-group-item-action active">
                                <i class="fas fa-cog me-2"></i> General Settings
                            </a>
                            <a href="#contact-settings" class="list-group-item list-group-item-action">
                                <i class="fas fa-map-marker-alt me-2"></i> Contact Information
                            </a>
                            <a href="#social-settings" class="list-group-item list-group-item-action">
                                <i class="fas fa-share-alt me-2"></i> Social Media
                            </a>
                            <a href="#seo-settings" class="list-group-item list-group-item-action">
                                <i class="fas fa-search me-2"></i> SEO Settings
                            </a>
                            <a href="#mail-settings" class="list-group-item list-group-item-action">
                                <i class="fas fa-envelope me-2"></i> Email Configuration
                            </a>
                            <a href="#order-settings" class="list-group-item list-group-item-action">
                                <i class="fas fa-shopping-cart me-2"></i> Order Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- General Settings -->
                <div class="card mb-4" id="general-settings">
                    <div class="card-header">
                        <h5><i class="fas fa-cog me-2"></i> General Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="settings[site_name]" value="{{ $generalSettingItems['site_name']->value ?? '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label">Currency Symbol</label>
                                <input type="text" class="form-control" id="currency" name="settings[currency]" value="{{ $generalSettingItems['currency']->value ?? '€' }}">
                                <small class="text-muted">Example: €, $, £</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="site_logo" class="form-label">Site Logo</label>
                                <input type="file" class="form-control image-preview-input" id="site_logo" name="site_logo" data-preview="logo-preview">
                                <div class="mt-2">
                                    <img src="{{ asset($generalSettingItems['site_logo']->value ?? 'uploads/logo.png') }}" alt="Logo Preview" id="logo-preview" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="theme_mode" class="form-label">Default Theme Mode</label>
                                <select class="form-select" id="theme_mode" name="settings[theme_mode]">
                                    <option value="light" {{ ($generalSettingItems['theme_mode']->value ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                                    <option value="dark" {{ ($generalSettingItems['theme_mode']->value ?? 'light') == 'dark' ? 'selected' : '' }}>Dark</option>
                                    <option value="system" {{ ($generalSettingItems['theme_mode']->value ?? 'system') == 'system' ? 'selected' : '' }}>System</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Settings -->
                <div class="card mb-4" id="contact-settings">
                    <div class="card-header">
                        <h5><i class="fas fa-map-marker-alt me-2"></i> Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="settings[phone]" value="{{ $contactSettingItems['phone']->value ?? '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="settings[email]" value="{{ $contactSettingItems['email']->value ?? '' }}">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Address</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="settings[address]" value="{{ $contactSettingItems['address']->value ?? '' }}">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="address" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Opening Hours</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="settings[opening_hours]" value="{{ $contactSettingItems['opening_hours']->value ?? '' }}">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="opening_hours" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="google_maps_iframe" class="form-label">Google Maps Embed</label>
                                <textarea class="form-control" id="google_maps_iframe" name="settings[google_maps_iframe]" rows="4">{{ $contactSettingItems['google_maps_iframe']->value ?? '' }}</textarea>
                                <small class="text-muted">Paste the full iframe code from Google Maps</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Settings -->
                <div class="card mb-4" id="social-settings">
                    <div class="card-header">
                        <h5><i class="fas fa-share-alt me-2"></i> Social Media</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fab fa-facebook text-primary me-1"></i> Facebook
                                </label>
                                <input type="url" class="form-control" name="settings[facebook]" value="{{ $socialSettingItems['facebook']->value ?? '' }}" placeholder="https://facebook.com/yourusername">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fab fa-instagram text-danger me-1"></i> Instagram
                                </label>
                                <input type="url" class="form-control" name="settings[instagram]" value="{{ $socialSettingItems['instagram']->value ?? '' }}" placeholder="https://instagram.com/yourusername">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fab fa-twitter text-info me-1"></i> Twitter
                                </label>
                                <input type="url" class="form-control" name="settings[twitter]" value="{{ $socialSettingItems['twitter']->value ?? '' }}" placeholder="https://twitter.com/yourusername">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fab fa-whatsapp text-success me-1"></i> WhatsApp
                                </label>
                                <input type="text" class="form-control" name="settings[whatsapp]" value="{{ $socialSettingItems['whatsapp']->value ?? '' }}" placeholder="+123456789">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fab fa-youtube text-danger me-1"></i> YouTube
                                </label>
                                <input type="url" class="form-control" name="settings[youtube]" value="{{ $socialSettingItems['youtube']->value ?? '' }}" placeholder="https://youtube.com/channel/yourchannelid">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="card mb-4" id="seo-settings">
                    <div class="card-header">
                        <h5><i class="fas fa-search me-2"></i> SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Meta Title</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="settings[meta_title]" value="{{ $seoSettingItems['meta_title']->value ?? '' }}">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="meta_title" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Meta Description</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <textarea class="form-control" name="settings[meta_description]" rows="3">{{ $seoSettingItems['meta_description']->value ?? '' }}</textarea>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="meta_description" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="settings[meta_keywords]" value="{{ $seoSettingItems['meta_keywords']->value ?? '' }}">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="meta_keywords" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <small class="text-muted">Separate keywords with commas</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mail Configuration Tab -->
                <div class="card mb-4" id="mail-settings">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope me-2"></i> Email Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="mail_driver" class="form-label">
                                    Mail Driver
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="mail_driver" name="settings[mail_driver]">
                                    <option value="smtp" {{ $mailSettingItems['mail_driver']->value == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ $mailSettingItems['mail_driver']->value == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ $mailSettingItems['mail_driver']->value == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ $mailSettingItems['mail_driver']->value == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    <option value="log" {{ $mailSettingItems['mail_driver']->value == 'log' ? 'selected' : '' }}>Log</option>
                                </select>
                                <small class="text-muted">Select the mail service to use for sending emails</small>
                            </div>

                            <div id="smtp_settings" class="{{ $mailSettingItems['mail_driver']->value != 'smtp' ? 'd-none' : '' }}">
                                <div class="col-md-12 mb-3">
                                    <label for="mail_host" class="form-label">
                                        SMTP Host
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-server"></i></span>
                                        <input type="text" class="form-control" id="mail_host" name="settings[mail_host]"
                                            value="{{ $mailSettingItems['mail_host']->value }}" placeholder="smtp.gmail.com">
                                    </div>
                                    <small class="text-muted">SMTP server address (e.g., smtp.gmail.com)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mail_port" class="form-label">
                                        SMTP Port
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-plug"></i></span>
                                        <input type="text" class="form-control" id="mail_port" name="settings[mail_port]"
                                            value="{{ $mailSettingItems['mail_port']->value ?? '587' }}" placeholder="587">
                                    </div>
                                    <small class="text-muted">SMTP port (usually 587 for TLS or 465 for SSL)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mail_encryption" class="form-label">
                                        SMTP Encryption
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="mail_encryption" name="settings[mail_encryption]">
                                        <option value="tls" {{ $mailSettingItems['mail_encryption']->value == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $mailSettingItems['mail_encryption']->value == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ $mailSettingItems['mail_encryption']->value == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                    <small class="text-muted">Encryption type (usually TLS)</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="mail_username" class="form-label">
                                        SMTP Username
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="mail_username" name="settings[mail_username]"
                                            value="{{ $mailSettingItems['mail_username']->value }}" placeholder="your@email.com">
                                    </div>
                                    <small class="text-muted">SMTP username (usually your email address)</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="mail_password" class="form-label">
                                        SMTP Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="mail_password" name="settings[mail_password]"
                                            value="{{ $mailSettingItems['mail_password']->value }}" placeholder="●●●●●●●●">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="mail_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">SMTP password or app password (for Gmail)</small>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="mail_from_address" class="form-label">
                                    From Email Address
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="mail_from_address" name="settings[mail_from_address]"
                                        value="{{ $mailSettingItems['mail_from_address']->value }}" placeholder="noreply@yoursite.com">
                                </div>
                                <small class="text-muted">The email address that will appear as sender</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="mail_from_name" class="form-label">
                                    From Name
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="mail_from_name" name="settings[mail_from_name]"
                                        value="{{ $mailSettingItems['mail_from_name']->value }}" placeholder="AISUKI Restaurant">
                                </div>
                                <small class="text-muted">The name that will appear as sender</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="mail_reply_to" class="form-label">
                                    Reply-To Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-reply"></i></span>
                                    <input type="email" class="form-control" id="mail_reply_to" name="settings[mail_reply_to]"
                                        value="{{ $mailSettingItems['mail_reply_to']->value }}" placeholder="info@yoursite.com">
                                </div>
                                <small class="text-muted">Email address to receive replies (if different from From Address)</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="mail_contact_to" class="form-label">
                                    Contact Form Recipient
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                                    <input type="email" class="form-control" id="mail_contact_to" name="settings[mail_contact_to]"
                                        value="{{ $mailSettingItems['mail_contact_to']->value }}" placeholder="contact@yoursite.com">
                                </div>
                                <small class="text-muted">Email address that will receive contact form submissions</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mail_enable_contact_form" name="settings[mail_enable_contact_form]"
                                        value="1" {{ $mailSettingItems['mail_enable_contact_form']->value ?? '0' == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mail_enable_contact_form">
                                        Enable contact form auto-reply
                                    </label>
                                </div>
                                <small class="text-muted">Send an automatic reply to users who submit the contact form</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mail_enable_notification" name="settings[mail_enable_notification]"
                                        value="1" {{ $mailSettingItems['mail_enable_notification']->value ?? '0' == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mail_enable_notification">
                                        Enable email notifications
                                    </label>
                                </div>
                                <small class="text-muted">Send email notifications for various system events</small>
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-paper-plane me-2"></i>Test Email Configuration</h6>
                                        <p class="card-text small">Verify your email configuration by sending a test email.</p>

                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control" id="test_email" placeholder="Enter recipient email address">
                                            <button class="btn btn-primary" type="button" id="test_email_btn">
                                                Send Test Email
                                            </button>
                                        </div>

                                        <div id="test_email_result" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Settings -->
                <div class="card mb-4" id="order-settings">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart me-2"></i> Order Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="delivery_fee" class="form-label">Delivery Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $generalSettingItems['currency']->value ?? '€' }}</span>
                                    <input type="number" step="0.01" min="0" class="form-control" id="delivery_fee" name="settings[delivery_fee]" value="{{ $orderSettingItems['delivery_fee']->value ?? '0.00' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $generalSettingItems['currency']->value ?? '€' }}</span>
                                    <input type="number" step="0.01" min="0" class="form-control" id="min_order_amount" name="settings[min_order_amount]" value="{{ $orderSettingItems['min_order_amount']->value ?? '0.00' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch flex items-center gap-2">
                                    <input class="form-check-input" type="checkbox" id="enable_delivery" name="settings[enable_delivery]" value="1" {{ ($orderSettingItems['enable_delivery']->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_delivery">Enable Delivery</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch flex items-center gap-2">
                                    <input class="form-check-input" type="checkbox" id="enable_pickup" name="settings[enable_pickup]" value="1" {{ ($orderSettingItems['enable_pickup']->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_pickup">Enable Pickup</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Store Address</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="settings[store_address]" value="{{ $orderSettingItems['store_address']->value ?? '' }}">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="store_address" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Pickup Instructions</label>
                                <div class="translatable-field">
                                    <div class="input-group">
                                        <textarea class="form-control" name="settings[pickup_instructions]" rows="3">{{ $orderSettingItems['pickup_instructions']->value ?? '' }}</textarea>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($languages as $language)
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#translationModal"
                                                    data-field="pickup_instructions" data-language="{{ $language->code }}" data-language-name="{{ $language->name }}">
                                                    {{ $language->name }} ({{ $language->code }})
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- Translation Modal -->
        <div class="modal fade" id="translationModal" tabindex="-1" aria-labelledby="translationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="translationModalLabel">Edit Translation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="translationField" value="">
                        <input type="hidden" id="translationLanguage" value="">

                        <div class="mb-3">
                            <label id="translationLabel" class="form-label"></label>
                            <textarea class="form-control" id="translationValue" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveTranslation">Add Translation</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Scrollspy for settings navigation
        $('.settings-nav a').on('click', function(e) {
            e.preventDefault();

            // Remove active class from all links
            $('.settings-nav a').removeClass('active');

            // Add active class to clicked link
            $(this).addClass('active');

            // Get the target section
            var target = $(this).attr('href');

            // Scroll to the target section with offset for fixed header
            $('html, body').animate({
                scrollTop: $(target).offset().top - 80
            }, 300);
        });

        // Update scrollspy on scroll
        $(window).on('scroll', function() {
            var scrollPosition = $(window).scrollTop() + 100;

            // Check each section
            $('div[id^="general-"], div[id^="contact-"], div[id^="social-"], div[id^="seo-"], div[id^="order-"]').each(function() {
                var target = $(this);
                var id = target.attr('id');

                if (target.offset().top <= scrollPosition && target.offset().top + target.outerHeight() > scrollPosition) {
                    $('.settings-nav a').removeClass('active');
                    $('.settings-nav a[href="#' + id + '"]').addClass('active');
                    return false;
                }
            });
        });

        // Image preview
        $('.image-preview-input').change(function(e) {
            var previewId = $(this).data('preview');
            var preview = $('#' + previewId);

            if (preview.length && this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.attr('src', e.target.result);
                };

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Handle translation modal
        $('#translationModal').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var field = button.data('field');
            var language = button.data('language');
            var languageName = button.data('language-name');

            // Set field and language values
            $('#translationField').val(field);
            $('#translationLanguage').val(language);

            // Set label
            $('#translationLabel').text(field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) + ' (' + languageName + ')');

            // Set current translation value if exists
            var translationValue = @json($translations);
            if (translationValue[language] && translationValue[language][field]) {
                $('#translationValue').val(translationValue[language][field].value || '');
            } else {
                $('#translationValue').val('');
            }
        });

        // Save translation
        $('#saveTranslation').click(function() {
            var field = $('#translationField').val();
            var language = $('#translationLanguage').val();
            var value = $('#translationValue').val();

            // Create hidden input for the translation
            var inputName = 'translations[' + language + '][' + field + ']';
            if ($('input[name="' + inputName + '"]').length) {
                $('input[name="' + inputName + '"]').val(value);
            } else {
                $('<input>').attr({
                    type: 'hidden',
                    name: inputName,
                    value: value
                }).appendTo('form');
            }

            // Close modal
            $('#translationModal').modal('hide');

            // Show success notification
            showNotification('Translation saved!', 'success');
        });

        // Function to show notifications
        function showNotification(message, type) {
            var notification = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');

            $('body').append(notification);
            notification.css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'z-index': '9999',
                'max-width': '300px'
            });

            setTimeout(function() {
                notification.alert('close');
            }, 3000);
        }
        // Show/hide SMTP settings based on driver selection
        $('#mail_driver').change(function() {
            if ($(this).val() === 'smtp') {
                $('#smtp_settings').removeClass('d-none');
            } else {
                $('#smtp_settings').addClass('d-none');
            }
        });

        // Toggle password visibility
        $('.toggle-password').click(function() {
            const targetId = $(this).data('target');
            const passwordInput = $('#' + targetId);
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Test email functionality
        $('#test_email_btn').click(function() {
            const resultContainer = $('#test_email_result');
            const testEmail = $('#test_email').val() || $('#mail_from_address').val();

            if (!testEmail) {
                resultContainer.html('<div class="alert alert-danger">Please enter a recipient email address</div>');
                return;
            }

            // Show loading message
            resultContainer.html('<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i>Sending test email...</div>');

            // Collect mail configuration from form
            const mailConfig = {
                driver: $('#mail_driver').val(),
                host: $('#mail_host').val(),
                port: $('#mail_port').val(),
                encryption: $('#mail_encryption').val(),
                username: $('#mail_username').val(),
                password: $('#mail_password').val(),
                from_address: $('#mail_from_address').val(),
                from_name: $('#mail_from_name').val(),
                test_email: testEmail
            };

            // Send AJAX request
            $.ajax({
                url: '{{ route("admin.settings.test-email") }}',
                type: 'POST',
                data: mailConfig,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        resultContainer.html('<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>' + response.message + '</div>');
                    } else {
                        resultContainer.html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + response.message + '</div>');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while testing the email configuration';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    resultContainer.html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + errorMessage + '</div>');
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .settings-nav-card {
        border-radius: 8px;
        overflow: hidden;
    }

    .settings-nav .list-group-item {
        border: none;
        padding: 12px 20px;
        border-left: 3px solid transparent;
        background-color: transparent;
    }

    .settings-nav .list-group-item.active {
        background-color: rgba(230, 28, 35, 0.1);
        color: var(--primary-color);
        border-left-color: var(--primary-color);
        font-weight: 500;
    }

    .settings-nav .list-group-item:hover:not(.active) {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .translatable-field .input-group-text {
        background-color: transparent;
    }

    .card {
        scroll-margin-top: 80px;
    }

    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    @media (max-width: 767.98px) {
        .settings-nav-card {
            position: static !important;
            margin-bottom: 20px;
        }
    }
</style>
@endpush