@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s">
    <div class="center-top d-flex justify-content-between">
        <div class="title">Add New Center</div>
    </div>
    <div class="add-center-main">
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">1</span>
                <div class="center-ttl">Details of the center</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Address</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#locationmodel">Add Address</a>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Telephone</label>
                            <div class="grp-right">
                                <input type="text" name="telephone" placeholder="+351 58548754784"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Manager Username:</label>
                            <div class="grp-right">
                                <input type="text" name="uname" placeholder="@manager"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Center Username:</label>
                            <div class="grp-right">
                                <input type="text" name="center-uname" placeholder="@center"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Featured Image:</label>
                            <div class="grp-right img-box upload-box">
                                <!--To give the control a modern look, I have applied a stylesheet in the parent span.-->
                                <output id="Filelist"></output>
                                <span class="btn btn-success fileinput-button">
                                <span>Upload</span>
                                <input type="file" name="files[]" id="files" multiple accept="image/jpeg, image/png, image/gif,"><br />
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Time table:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link add-time" data-toggle="modal" data-target="#centerhours">Add Time table</a>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Email</label>
                            <div class="grp-right">
                                <input type="email" name="email" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Manager password:</label>
                            <div class="grp-right">
                                <input type="text" name="uname" placeholder="123456789"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Center password:</label>
                            <div class="grp-right">
                                <input type="text" name="center-uname" placeholder="123456789"/>
                            </div>
                        </div>
                        <div class="grp-field txtarea">
                            <label>Description:</label>
                            <div class="grp-right img-box upload-box">
                                <textarea name="desc"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 last-col">
                        <div class="gal-bottom">
                            <div class="grp-field">
                                <label>Gallery</label>
                                <div class="grp-right">
                                    <output id="Filelist"></output><span class="btn btn-success fileinput-button">
                                    <span>Upload</span>
                                    <input type="file" name="" id="files1" multiple accept="image/jpeg, image/png, image/gif,"><br />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">2</span>
                <div class="center-ttl">Company Details</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Company Name:</label>
                            <div class="grp-right">
                                <input type="text" name="comp-name" placeholder="Terra Heal Massage Center"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Address:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#companyaddress">Add Address</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>NIF Company:</label>
                            <div class="grp-right">
                                <input type="text" name="telephone" placeholder="125478416554"/>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">3</span>
                <div class="center-ttl">Owner Details</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Name:</label>
                            <div class="grp-right">
                                <input type="text" name="name" placeholder="Rohit"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Email:</label>
                            <div class="grp-right">
                                <input type="email" name="email" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field txtarea">
                            <label>Financial situation:</label>
                            <div class="grp-right">
                                <textarea name="financial"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Surname:</label>
                            <div class="grp-right">
                                <input type="text" name="Surname" placeholder="yadav"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Telephone:</label>
                            <div class="grp-right">
                                <input type="text" name="tel" placeholder="+351 2545458745"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Alternative Tel:</label>
                            <div class="grp-right">
                                <input type="text" name="a-tel" placeholder="+351 2548745414"/>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">4</span>
                <div class="center-ttl">payments and payouts accounts</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>IBAN:</label>
                            <div class="grp-right">
                                <input type="text" name="iban" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Google pay:</label>
                            <div class="grp-right">
                                <input type="text" name="gpay" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Hipay:</label>
                            <div class="grp-right">
                                <input type="text" name="Hipay" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>PayPal:</label>
                            <div class="grp-right">
                                <input type="text" name="paypal" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Apple Pay:</label>
                            <div class="grp-right">
                                <input type="text" name="a-pay" placeholder="youremail@14.com"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Other:</label>
                            <div class="grp-right">
                                <select>
                                    <option>Select</option>
                                    <option>Select 1</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">5</span>
                <div class="center-ttl">Agrements for Payments</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>% of sales:</label>
                            <div class="grp-right">
                                <input type="text" name="sales" placeholder="80"/>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Initial amount to pay:</label>
                            <div class="grp-right">
                                <input type="text" name="init-pay" placeholder="8000$"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Fixed Amount P.M.:</label>
                            <div class="grp-right">
                                <input type="text" name="amount" placeholder="54150$"/>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">6</span>
                <div class="center-ttl">Upload documents</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Franchise Contact:</label>
                            <div class="grp-right">
                                <div class="file">
                                    <label class="file-label">
                                    <input class="file-input" type="file" name="resume">
                                    <span class="file-cta">
                                    <span class="file-label">Add New Documents</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grp-field">
                            <label>Registration:</label>
                            <div class="grp-right">
                                <div class="file">
                                    <label class="file-label">
                                    <input class="file-input" type="file" name="resume">
                                    <span class="file-cta">
                                    <span class="file-label">Add New Documents</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>ID/Passport:</label>
                            <div class="grp-right">
                                <div class="file">
                                    <label class="file-label">
                                    <input class="file-input" type="file" name="resume">
                                    <span class="file-cta">
                                    <span class="file-label">Add New Documents</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">7</span>
                <div class="center-ttl">Consulting Franchise services For this Center</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Investment Budget:</label>
                            <div class="grp-right">
                                <input type="text" name="budget" placeholder="8000$"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Services:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#listservices">List of services</a>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">8</span>
                <div class="center-ttl">massages and therapies</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Massages:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#listmassages">List of Massages</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Therapies:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#listtherapies">List of Therapies</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h4>Home/Hotel Visits</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Massages:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link">List of Massages</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Therapies:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link">List of Therapies</a>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center add-section">
                        <div class="col-md-3">
                            <span>10 km</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span>15 km</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span>20 km</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span>25 km</span>
                            <div class="add-col d-flex align-items-center">
                                <input type="text"/><i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <a href="javascript:void(0);" class="add-more">Add More</a>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">9</span>
                <div class="center-ttl">Vouchers</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Vouchers:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#vouchers">List of Vouchers</a>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">10</span>
                <div class="center-ttl">Packs</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <div class="row">
                    <div class="col-md-6">
                        <div class="grp-field">
                            <label>Packs:</label>
                            <div class="grp-right">
                                <a href="javascript:void(0);" class="add-link" data-toggle="modal" data-target="#packs">List of Packs</a>
                            </div>
                        </div>
                    </div>
                    <div class="last-colmn">
                        <a href="javascript:void(0);" class="btn-custom save">Save</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-row d-flex justify-content-between">
            <div class="center-left">
                <span class="number">11</span>
                <div class="center-ttl">Variable functions for this center</div>
            </div>
            <div class="center-right"> <a href="javascript:void(0);" class="add-d btn-custom">Add</a> </div>
            <div class="center-bottom-sec">
                <ul class="switch-sec">
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">Deactivate</button>
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
                                <button class="btn btn-sm btn-default active">Deactivate</button>
                                <button class="btn btn-sm btn-primary">Activate</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">Deactivate</button>
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
                                <button class="btn btn-sm btn-default">Deactivate</button>
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
                                <button class="btn btn-sm btn-default active">Deactivate</button>
                                <button class="btn btn-sm btn-primary">Activate</button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="label-left">
                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</span>
                        </div>
                        <div class="class-switch">
                            <div class="btn-group btn-toggle"> 
                                <button class="btn btn-sm btn-default">Deactivate</button>
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
                                <button class="btn btn-sm btn-default">Deactivate</button>
                                <button class="btn btn-sm btn-primary active">Activate</button>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="last-colmn">
                    <a href="javascript:void(0);" class="btn-custom save">Save</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
