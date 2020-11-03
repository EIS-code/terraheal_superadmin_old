@extends('superadmin.layouts.app')

@section('content')

@push('scripts')
    <script difer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_STATIC_MAP_API_KEY') }}" defer></script>
@endpush
<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="center-top d-flex justify-content-between">
        <div class="title">{{ __('Add New Center') }}</div>
    </div>
    <div class="add-center-main">
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">1</span>
                <div class="center-ttl">{{ __('Details of the center') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <form method="POST" action="{{ route('centers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="grp-field">
                                <label>{{ __('Address') }}</label>
                                <div class="grp-right">
                                    <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#locationmodel">{{ __('Add Address') }}</a>
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Telephone') }} : </label>
                                <div class="grp-right">
                                    <input type="text" name="tel_number" placeholder="{{ __('+351 58548754784') }}" value="{{ old('tel_number') }}" />
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Manager Username') }}:</label>
                                <div class="grp-right">
                                    <input type="text" name="manager_user_name" placeholder="{{ __('@manager') }}" value="{{ old('manager_user_name') }}" />
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Center Username') }} : </label>
                                <div class="grp-right">
                                    <input type="text" name="shop_user_name" placeholder="@center" value="{{ old('shop_user_name') }}"/>
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Featured Image') }} : </label>
                                <div class="grp-right img-box upload-box">
                                    <!--To give the control a modern look, I have applied a stylesheet in the parent span.-->
                                    <output id="Filelist"></output>
                                    <span class="btn btn-success fileinput-button">
                                    <span>{{ __('Upload') }} : </span>
                                    <input type="file" name="featured_image[]" id="files" multiple accept="image/jpeg, image/png, image/gif,"><br />
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="grp-field">
                                <label>{{ __('Time table') }} : </label>
                                <div class="grp-right">
                                    <a href="javascript:void(0);" class="add-link add-time" data-toggle="modal" data-target="#centerhours">{{ __('Add Time table') }} : </a>
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Email') }} : </label>
                                <div class="grp-right">
                                    <input type="email" name="email" placeholder="{{ __('youremail@14.com') }}" value="{{ old('email') }}" />
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Manager password') }} : </label>
                                <div class="grp-right">
                                    <input type="password" name="manager_password" placeholder="{{ __('123456789') }}" />
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Center password') }} : </label>
                                <div class="grp-right">
                                    <input type="password" name="shop_password" placeholder="{{ __('123456789') }}" />
                                </div>
                            </div>
                            <div class="grp-field txtarea">
                                <label>{{ __('Description') }} : </label>
                                <div class="grp-right img-box upload-box">
                                    <textarea name="description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 last-col">
                            <div class="gal-bottom">
                                <div class="grp-field">
                                    <label>{{ __('Gallery') }} : </label>
                                    <div class="grp-right">
                                        <output id="Filelist"></output><span class="btn btn-success fileinput-button">
                                        <span>{{ __('Upload') }} : </span>
                                        <input type="file" name="gallery[]" id="files1" multiple accept="image/jpeg, image/png, image/gif,"><br />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="last-colmn">
                            <button type="submit" class="btn-custom save">{{ __('Save') }} : </a>
                        </div>
                    </div>

                    <div class="modal fade" id="locationmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Center Location') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>
                                <div class="modal-body">
                                    <div class="model-inner">
                                        <div class="mode-field half">
                                            <div class="field-grp">
                                                <label>{{ __('Center Country') }}</label>
                                                <select name="country_id" id="countries" data-is-get-provinces="true" data-selectbox-province-id="#provinces" data-selected-id="{{ old('country_id') }}" autocomplete="off">
                                                    <option value="">{{ __('Select...') }}</option>

                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected="true"' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="field-grp">
                                                <label>{{ __('Center State') }}</label>
                                                <select name="province_id" id="provinces" data-is-get-cities="true" data-selectbox-city-id="#cities" data-selected-id="{{ old('province_id') }}" autocomplete="off">
                                                    <option data-is-default="true" value="">{{ __('Select...') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mode-field">
                                            <div class="field-grp">
                                                <label>{{ __('Center Address') }}</label>
                                                <input type="text" name="address" value="{{ old('address') }}" />
                                            </div>
                                            <div class="field-grp">
                                                <label>{{ __('Center Address Line 2') }}</label>
                                                <input type="text" name="address2" value="{{ old('address2') }}" />
                                            </div>
                                        </div>
                                        <div class="mode-field half">
                                            <div class="field-grp">
                                                <label>{{ __('Center City') }}</label>
                                                <select id="cities" name="city_id" data-selected-id="{{ old('city_id') }}" autocomplete="off">
                                                    <option data-is-default="true" value="">{{ __('Select...') }}</option>
                                                </select>
                                            </div>
                                            <div class="field-grp">
                                                <label>{{ __('Pincode') }}</label>
                                                <input type="text" name="pin_code" value="{{ old('pin_code') }}" />
                                            </div>
                                        </div>
                                        <div class="custom-row row">
                                            <div class="col-md-8">
                                                <div class="model-map">
                                                    <!-- <img src="images/map.png"/> -->
                                                    <div id="map"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mode-field">
                                                    <div class="field-grp">
                                                        <label>{{ __('Latitude') }}</label>
                                                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" />
                                                    </div>
                                                    <div class="field-grp">
                                                        <label>{{ __('Longitude') }}</label>
                                                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" />
                                                    </div>
                                                    <div class="field-grp">
                                                        <label>{{ __('Zoom Level') }}</label>
                                                        <input type="text" name="zoom" id="zoom" value="{{ old('zoom') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="apply" data-dismiss="modal" aria-label="Close">Apply</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="centerhours" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form method="POST" action="{{ route('centers.timing.create') }}" class="w100">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Center Hours</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="model-inner">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="slot-left">
                                                        <ul>
                                                            <li>
                                                                <label>Sun</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default active radioToggle" data-value="0" data-class="sun">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary inactive radioToggle" data-value="1" data-class="sun">Open</button>

                                                                            <input type="radio" class="disp-none sun" value="0" name="sunday" checked="" />
                                                                            <input type="radio" class="disp-none sun" value="1" name="sunday" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Mon</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default inactive radioToggle" data-value="0" data-class="mon">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary active radioToggle" data-value="1" data-class="mon">Open</button>

                                                                            <input type="radio" class="disp-none mon" value="0" name="monday" />
                                                                            <input type="radio" class="disp-none mon" value="1" name="monday" checked="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Tue</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default inactive radioToggle" data-value="0" data-class="tue">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary active radioToggle" data-value="1" data-class="tue">Open</button>

                                                                            <input type="radio" class="disp-none tue" value="0" name="tuesday" />
                                                                            <input type="radio" class="disp-none tue" value="1" name="tuesday" checked="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Wed</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default inactive radioToggle" data-value="0" data-class="wed">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary active radioToggle" data-value="1" data-class="wed">Open</button>

                                                                            <input type="radio" class="disp-none wed" value="0" name="wednesday" />
                                                                            <input type="radio" class="disp-none wed" value="1" name="wednesday" checked="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Thu</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default inactive radioToggle" data-value="0" data-class="thu">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary active radioToggle" data-value="1" data-class="thu">Open</button>

                                                                            <input type="radio" class="disp-none thu" value="0" name="thursday" />
                                                                            <input type="radio" class="disp-none thu" value="1" name="thursday" checked="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Fri</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default inactive radioToggle" data-value="0" data-class="fri">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary active radioToggle" data-value="1" data-class="fri">Open</button>

                                                                            <input type="radio" class="disp-none fri" value="0" name="friday" />
                                                                            <input type="radio" class="disp-none fri" value="1" name="friday" checked="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Sat</label>
                                                                <div class="switch-rt">
                                                                    <div class="class-switch">
                                                                        <div class="btn-group btn-toggle"> 
                                                                            <button type="button" class="btn btn-sm btn-default inactive radioToggle" data-value="0" data-class="sat">Close</button>
                                                                            <button type="button" class="btn btn-sm btn-primary active radioToggle" data-value="1" data-class="sat">Open</button>

                                                                            <input type="radio" class="disp-none sat" value="0" name="saturday" />
                                                                            <input type="radio" class="disp-none sat" value="1" name="saturday" checked="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="slot-right">
                                                        <h2>24 hours</h2>
                                                        <div class="slot-col">
                                                            <ul>
                                                                <li>Open at</li>
                                                                <li>11 am</li>
                                                                <li>11 am</li>
                                                                <li>11 am</li>
                                                                <li>11 am</li>
                                                            </ul>
                                                            <ul>
                                                                <li>Close at</li>
                                                                <li>7 am</li>
                                                                <li>7 am</li>
                                                                <li>7 am</li>
                                                                <li>4 am</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="javascript:void(0);" class="apply" data-dismiss="modal" aria-label="Close">Apply</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (!empty(old('country_id')))
                        @push('scripts')
                            <script type="text/javascript">
                                setTimeout(function() {
                                    $("#countries").change();
                                }, 2000);
                            </script>
                        @endpush
                    @endif

                    @if (!empty(old('province_id')))
                        @push('scripts')
                            <script type="text/javascript">
                                setTimeout(function() {
                                    $("#provinces").change();
                                }, 3000);
                            </script>
                        @endpush
                    @endif
                </form>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">2</span>
                <div class="center-ttl">{{ __('Company Details') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <form method="POST" action="{{ route('centers.company.create') }}" class="w100">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="grp-field">
                                <label>{{ __('Company Name') }} : </label>
                                <div class="grp-right">
                                    <input type="text" name="name" placeholder="{{ __('Terra Heal Massage Center') }}" value="{{ old('name') }}" />
                                </div>
                            </div>
                            <div class="grp-field">
                                <label>{{ __('Address') }} : </label>
                                <div class="grp-right">
                                    <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#companyaddress">{{ __('Add Address') }} : </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="grp-field">
                                <label>{{ __('NIF Company') }} : </label>
                                <div class="grp-right">
                                    <input type="text" name="nif" placeholder="{{ __('125478416554') }}" value="{{ old('nif') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="last-colmn">
                            <button type="submit" class="btn-custom save">{{ __('Save') }}</button>
                        </div>
                    </div>
                    <div class="modal fade" id="companyaddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Company Address</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>
                                <div class="modal-body">
                                    <div class="model-inner">
                                        <div class="company-address">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mode-field">
                                                        <div class="field-grp">
                                                            <label>Company Address</label>
                                                            <input type="text" name="address" value="{{ old('address') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="mode-field half">
                                                        <div class="field-grp">
                                                            <label>{{ __('Company Country') }}</label>
                                                            <select name="country_id" id="countries1" data-is-get-provinces="true" data-selectbox-province-id="#provinces1" data-selected-id="{{ old('country_id') }}" autocomplete="off">
                                                                <option data-is-default="true" value="">{{ __('Select...') }}</option>

                                                                @foreach ($countries as $country)
                                                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected="true"' : '' }}>{{ $country->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="field-grp">
                                                            <label>{{ __('Company State') }}</label>
                                                            <select name="province_id" id="provinces1" data-is-get-cities="true" data-selectbox-city-id="#cities1" data-selected-id="{{ old('province_id') }}" autocomplete="off">
                                                                <option data-is-default="true" value="">{{ __('Select...') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="model-map">
                                                        <img src="images/map.png"/>
                                                        <a class="clear" href="javascript:void(0);">Clear address</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mode-field">
                                                        <div class="field-grp">
                                                            <label>{{ __('Company City') }}</label>
                                                            <select id="cities1" name="city_id" data-selected-id="{{ old('city_id') }}" autocomplete="off">
                                                                <option data-is-default="true" value="">{{ __('Select...') }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="field-grp">
                                                            <label>Latitude</label>
                                                            <input type="text" name="latitude" value="{{ old('latitude') }}" />
                                                        </div>
                                                        <div class="field-grp">
                                                            <label>Longitude</label>
                                                            <input type="text" name="longitude" value="{{ old('longitude') }}" />
                                                        </div>
                                                        <div class="field-grp">
                                                            <label>Zoom Level</label>
                                                            <input type="text" name="zoom" value="{{ old('zoom') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="apply" data-dismiss="modal" aria-label="Close">Apply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if (!empty(old('country_id')))
                    @push('scripts')
                        <script type="text/javascript">
                            setTimeout(function() {
                                $("#countries").change();
                            }, 2000);
                        </script>
                    @endpush
                @endif

                @if (!empty(old('province_id')))
                    @push('scripts')
                        <script type="text/javascript">
                            setTimeout(function() {
                                $("#provinces").change();
                            }, 3000);
                        </script>
                    @endpush
                @endif
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">3</span>
                <div class="center-ttl">{{ __('Owner Details') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Name') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="name" placeholder="{{ __('Rohit') }}"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Email') }} : </label>
                            <div class="grp-right">
                                <input type="email" name="email" placeholder="{{ __('youremail@14.com') }}"/>
                            </div>
                        </div>
                        <div class="grp-field txtarea">
                            <label>{{ __('Financial situation') }} : </label>
                            <div class="grp-right">
                                <textarea name="financial"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Surname') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="Surname" placeholder="yadav"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Telephone') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="tel" placeholder="{{ __('+351 2545458745') }}"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Alternative Tel') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="a-tel" placeholder="{{ __('+351 2548745414') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">4</span>
                <div class="center-ttl">{{ __('payments and payouts accounts') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('IBAN') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="iban" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Google pay') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="gpay" placeholder="{{ __('youremail@14.com') }}"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Hipay') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="Hipay" placeholder="{{ __('youremail@14.com') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('PayPal') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="paypal" placeholder="{{ __('youremail@14.com') }}"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Apple Pay') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="a-pay" placeholder="{{ __('youremail@14.com') }}"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Other') }} : </label>
                            <div class="grp-right">
                                <select>
                                    <option>{{ __('Select') }}</option>
                                    <option>Select 1</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">5</span>
                <div class="center-ttl">{{ __('Agrements for Payments') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('% of sales') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="sales" placeholder="{{ __('80') }}"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Initial amount to pay') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="init-pay" placeholder="{{ __('8000$') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Fixed Amount P.M.') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="amount" placeholder="{{ __('54150$') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">6</span>
                <div class="center-ttl">{{ __('Upload documents') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Franchise Contact') }} : </label>
                            <div class="grp-right">
                                <div class="file">
                                    <label class="file-label">
                                    <input class="file-input" type="file" name="resume">
                                    <span class="file-cta">
                                    <span class="file-label">{{ __('Add New Documents') }}</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>{{ __('Registration') }} : </label>
                            <div class="grp-right">
                                <div class="file">
                                    <label class="file-label">
                                    <input class="file-input" type="file" name="resume">
                                    <span class="file-cta">
                                    <span class="file-label">{{ __('Add New Documents') }}</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('ID/Passport') }} : </label>
                            <div class="grp-right">
                                <div class="file">
                                    <label class="file-label">
                                    <input class="file-input" type="file" name="resume">
                                    <span class="file-cta">
                                    <span class="file-label">{{ __('Add New Documents') }}</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">7</span>
                <div class="center-ttl">{{ __('Consulting Franchise services For this Center') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Investment Budget') }} : </label>
                            <div class="grp-right">
                                <input type="text" name="budget" placeholder="{{ __('8000$') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Services') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#listservices">{{ __('List of services') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">8</span>
                <div class="center-ttl">{{ __('massages and therapies') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Massages') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#listmassages">{{ __('List of Massages') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Therapies') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#listtherapies">{{ __('List of Therapies') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h4>{{ __('Home/Hotel Visits') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Massages') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link">{{ __('List of Massages') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Therapies') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link">{{ __('List of Therapies') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center add-section">
                        <div class="col-md-3">
                            <span>{{ __('10 km') }}</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span>{{ __('15 km') }}</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span>{{ __('20 km') }}</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span>{{ __('25 km') }}</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <a href="javascript:void(0);" class="add-more">{{ __('Add More') }}</a>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">9</span>
                <div class="center-ttl">{{ __('Vouchers') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Vouchers') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#vouchers">{{ __('List of Vouchers') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">10</span>
                <div class="center-ttl">{{ __('Packs') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>{{ __('Packs') }} : </label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#packs">{{ __('List of Packs') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">11</span>
                <div class="center-ttl">{{ __('Variable functions for this center') }}</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">{{ __('Add') }}</a> </div>
            <div class="center-bottom-sec">
                <ul class="switch-sec">
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary active">Activate</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default active">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary">{{ __('Activate') }}</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary active">{{ __('Activate') }}</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary active">{{ __('Activate') }}</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default active">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary">{{ __('Activate') }}</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary active">{{ __('Activate') }}</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">{{ __('Deactivate') }}</button>
                                <button class="btn btn-sm btn-primary active">{{ __('Activate') }}</button>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="last-colmn">
                    <a href="javascript:void(0);" class="btn-custom save">{{ __('Save') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
