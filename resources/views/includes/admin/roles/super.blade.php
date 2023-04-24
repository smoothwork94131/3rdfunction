<li>
    <a href="#categories" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-cart"></i>{{ __('Categories') }}
    </a>
    <ul class="collapse list-unstyled" id="categories" data-parent="#accordion">
        <li
            class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category') active @endif">
            <a href="{{ route('admin-cat-index') }}"><span>{{ __('Category') }}</span></a>
        </li>
    </ul>
</li>
<li>
    <a href="#order" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i
            class="fas fa-hand-holding-usd"></i>{{ __('Orders') }}</a>
    <ul class="collapse list-unstyled" id="order" data-parent="#accordion">
        <li>
            <a href="{{route('admin-order-index')}}"> {{ __('All Orders') }}</a>
        </li>
        <li>
            <a href="{{route('admin-order-pending')}}"> {{ __('Pending Orders') }}</a>
        </li>
        <li>
            <a href="{{route('admin-order-processing')}}"> {{ __('Processing Orders') }}</a>
        </li>
        <li>
            <a href="{{route('admin-order-completed')}}"> {{ __('Completed Orders') }}</a>
        </li>
        <li>
            <a href="{{route('admin-order-declined')}}"> {{ __('Declined Orders') }}</a>
        </li>
    </ul>
</li>
<li>
    <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-cart"></i>{{ __('Home Products') }}
    </a>
    <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-prod-index') }}"><span>{{ __('All Home Products') }}</span></a>
        </li>
        {{-- <li>
            <a href="{{ route('admin-prod-catalog-index') }}"><span>{{ __('Product Catalogs') }}</span></a>
        </li> --}}
        <li>
            <a href="{{ route('admin-prod-import') }}"><span>{{ __('Bulk Product Upload') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#menu3" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-user"></i>{{ __('Customers') }}
    </a>
    <ul class="collapse list-unstyled" id="menu3" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-user-index') }}"><span>{{ __('Customers List') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-withdraw-index') }}"><span>{{ __('Withdraws') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-user-image') }}"><span>{{ __('Customer Default Image') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-user-track', 'all') }}"><span>{{ __('Customers Tracks') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#verification" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-verification-check"></i>{{ __('Site Verifications') }}
    </a>
    <ul class="collapse list-unstyled" id="verification" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-vr-index') }}"><span>{{ __('All Verifications') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-vr-pending') }}"><span>{{ __('Pending Verifications') }}</span></a>
        </li>
    </ul>
</li>

{{-- <li>
    <a href="#license" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-check-circle"></i>{{ __('Verified License') }}
    </a>
    <ul class="collapse list-unstyled" id="license" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-verification-index') }}"><span>{{ __('Verification Plans') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-verification-pending') }}"><span>{{ __('Pending') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-verification-approved') }}"><span>{{ __('Approved') }}</span></a>
        </li>
    </ul>
</li> --}}

<hr>

<li>
    <a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-speech-comments"></i>{{ __('Product Discussion') }}
    </a>
    <ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-rating-index') }}"><span>{{ __('Product Reviews') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-comment-index') }}"><span>{{ __('Comments') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-report-index') }}"><span>{{ __('Reports') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('admin-coupon-index') }}" class=" wave-effect"><i class="fas fa-percentage"></i>{{ __('Set
        Coupons') }}</a>
</li>

<hr>

<li>
    <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cogs"></i>{{ __('General Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-gs-logo') }}"><span>{{ __('Logo') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-fav') }}"><span>{{ __('Favicon') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-gs-load') }}"><span>{{ __('Loader') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-shipping-index') }}"><span>{{ __('Shipping Methods') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-package-index') }}"><span>{{ __('Packaging') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-pick-index') }}"><span>{{ __('Pickup Locations') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-solo') }}"><span>{{ __('Site Mode') }}</span></a>
        </li>
    </ul>
</li>



<li>
    <a href="#homepage" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-edit"></i>{{ __('Website Contents') }}
    </a>
    <ul class="collapse list-unstyled" id="homepage" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-gs-footer') }}"><span>{{ __('Footer') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-gs-popup') }}"><span>{{ __('Popup Banner') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-gs-agepopup') }}"><span>{{ __('Age Popup') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-gs-error-banner') }}"><span>{{ __('Error Banner') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-gs-maintenance') }}"><span>{{ __('Website Maintenance') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-gs-contents') }}"><span>{{ __('Home Page Settings') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-sl-index') }}"><span>{{ __('Sliders') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-service-index') }}"><span>{{ __('Services') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-best-seller') }}"><span>{{ __('Right Side Banner1') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-big-save') }}"><span>{{ __('Right Side Banner2') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-sb-index') }}"><span>{{ __('Top Small Banners') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-sb-large') }}"><span>{{ __('Large Banners') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-sb-bottom') }}"><span>{{ __('Bottom Small Banners') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-review-index') }}"><span>{{ __('Reviews') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-partner-index') }}"><span>{{ __('Partners') }}</span></a>
        </li>


        <li>
            <a href="{{ route('admin-ps-customize') }}"><span>{{ __('Home Page Customization') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#menu" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-file-code"></i>{{ __('Menu Page Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="menu" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-faq-index') }}"><span>{{ __('FAQ Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-contact') }}"><span>{{ __('Contact Us Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-page-index') }}"><span>{{ __('Other Pages') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#productview" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-box"></i>{{ __('Product View Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="productview" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-gs-prodimage') }}"><span>{{ __('Product Default Image') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-productview-home') }}"><span>{{ __('Home Product View') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-productview-filtered') }}"><span>{{ __('Category/Search View') }}</span></a>
        </li>
        <!-- <li>
            <a href="{{ route('admin-productview-vendor') }}"><span>{{ __('Vendor Product View') }}</span></a>
        </li> -->
    </ul>
</li>

<li>
    <a href="#emails" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-at"></i>{{ __('Email Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="emails" data-parent="#accordion">
        <li><a href="{{route('admin-mail-index')}}"><span>{{ __('Email Template') }}</span></a></li>
        <li><a href="{{route('admin-mail-config')}}"><span>{{ __('Email Configurations') }}</span></a></li>
        <li><a href="{{route('admin-group-show')}}"><span>{{ __('Group Email') }}</span></a></li>
    </ul>
</li>
{{-- <li>
    <a href="#payments" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-file-code"></i>{{ __('Payment Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="payments" data-parent="#accordion">
        <li><a href="{{route('admin-gs-payments')}}"><span>{{__('Payment Information')}}</span></a></li>
        <li><a href="{{route('admin-payment-index')}}"><span>{{ __('Payment Gateways') }}</span></a></li>
        <li><a href="{{route('admin-currency-index')}}"><span>{{ __('Currencies') }}</span></a></li>
        <li><a href="{{route('admin-currency-index')}}"><span>{{ __('Crypto Info') }}</span></a></li>
    </ul>
</li>
<li>
    <a href="#socials" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-paper-plane"></i>{{ __('Social Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="socials" data-parent="#accordion">
        <li><a href="{{route('admin-social-index')}}"><span>{{ __('Social Links') }}</span></a></li>
        <li><a href="{{route('admin-social-facebook')}}"><span>{{ __('Facebook Login') }}</span></a></li>
        <li><a href="{{route('admin-social-google')}}"><span>{{ __('Google Login') }}</span></a></li>
    </ul>
</li> --}}
<li>
    <a href="#langs" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-language"></i>{{ __('Language Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="langs" data-parent="#accordion">
        <li><a href="{{route('admin-lang-index')}}"><span>{{ __('Website Language') }}</span></a></li>
        <li><a href="{{route('admin-tlang-index')}}"><span>{{ __('Admin Panel Language') }}</span></a></li>

    </ul>
</li>
<li>
    <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-wrench"></i>{{ __('SEO Tools') }}
    </a>
    <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-prod-popular',30) }}"><span>{{ __('Popular Products') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-seotool-analytics') }}"><span>{{ __('Google Analytics') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-seotool-keywords') }}"><span>{{ __('Website Meta Keywords') }}</span></a>
        </li>
    </ul>
</li>

<hr>


<li>
    <a href="#blog" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-fw fa-newspaper"></i>{{ __('Blog') }}
    </a>
    <ul class="collapse list-unstyled" id="blog" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-cblog-index') }}"><span>{{ __('Categories') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-blog-index') }}"><span>{{ __('Posts') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#msg" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-fw fa-newspaper"></i>{{ __('Messages') }}
    </a>
    <ul class="collapse list-unstyled" id="msg" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-message-index') }}"><span>{{ __('Tickets') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-message-dispute') }}"><span>{{ __('Disputes') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-message-sendmessage') }}"><span>{{ __('Send Text Messages') }}</span></a>
        </li>

    </ul>
</li>

<hr>


<li>
    <a href="{{ route('admin-staff-index') }}" class=" wave-effect"><i class="fas fa-user-secret"></i>{{ __('Manage
        Staff') }}</a>
</li>

<li>
    <a href="{{ route('admin-subs-index') }}" class=" wave-effect"><i class="fas fa-users-cog mr-2"></i>{{
        __('Subscribers') }}</a>
</li>

<li>
    <a href="{{ route('admin-role-index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Manage Roles')
        }}</a>
</li>
<li>
    <a href="{{ route('admin-cache-clear') }}" class=" wave-effect"><i class="fas fa-sync"></i>{{ __('Clear Cache') }}
    </a>
</li>
<li><a href="{{route('admin-generate-backup')}}"> {{ __('Generate Backup') }}</a></li>