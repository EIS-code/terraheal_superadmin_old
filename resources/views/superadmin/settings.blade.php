@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s" id="list">
    <div class="center-top d-flex justify-content-between">
        <div class="title">Settings</div>
    </div>
    <div class="settings-main">
        <div class="setting-inner">
            <div class="setting-top">
                <h3>Welcome Admin</h3>
                <p>Here you will be able to change all the settings.</p>
            </div>
            <div class="setting-list">
                <ul>
                    <li>
                        <div class="st-round"></div>
                        <label>General Settings</label>
                        <p>Here you can change the basic account settings</p>
                    </li>
                    <li>
                        <div class="st-round"></div>
                        <label>User Settings</label>
                        <p>Here you can change the basic account settings</p>
                    </li>
                    <li>
                        <div class="st-round"></div>
                        <label>Support Requests</label>
                        <p>Here you can change the basic account settings</p>
                    </li>
                    <li>
                        <div class="st-round"></div>
                        <label>Referral Programs</label>
                        <p>Here you can change the basic account settings</p>
                    </li>
                    <li>
                        <div class="st-round"></div>
                        <label>Payment Settings</label>
                        <p>Here you can change the basic account settings</p>
                    </li>
                    <li>
                        <div class="st-round"></div>
                        <label>App Settings</label>
                        <p>Here you can change the basic account settings</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
