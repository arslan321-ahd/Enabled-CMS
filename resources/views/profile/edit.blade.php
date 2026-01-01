@extends('admin.partials.layouts')
@section('title', 'Edit Profile')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Profile</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                            </li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12">

                {{-- Personal Information --}}
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Personal Information</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>

                    <div class="card-body pt-0">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-danger">Delete Account</h4>
                    </div>

                    <div class="card-body pt-0">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>

    </div>
    {{-- <x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            @if (session('status') === 'profile-updated')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Profile updated successfully.'

                });
            @endif

            @if (session('status') === 'password-updated')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Password updated successfully.'
                });
            @endif

            @if (session('status') === 'verification-link-sent')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'A new verification link has been sent to your email address.'
                });
            @endif

            @if (session('status') === 'account-deleted')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Account deleted successfully.'
                });
            @endif
            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fix the errors and try again.'
                });
            @endif

        });
    </script>

@endsection
