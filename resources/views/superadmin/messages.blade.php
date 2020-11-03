@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s" id="list">
    <div class="center-top d-flex justify-content-between">
        <div class="title">Massage</div>
    </div>
    <div class="massage-inner-main">
        <div class="row">
            <div class="col-md-4">
                <div class="search-top">
                    <input type="text" placeholder="Search">
                    <button type="submit"><img src="{{ asset('images/search.png') }}" alt="search"></button>
                </div>
                <div class="msg-left">
                    <ul>
                        <li>
                            <div class="msg-top d-flex">
                                <figure><img src="{{ asset('images/rs2.png') }}" alt=""/></figure>
                                <div class="fig-right">
                                    <h5>Therapist</h5>
                                    <span>Mildred Wilson</span>
                                </div>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                        </li>
                        <li>
                            <div class="msg-top d-flex">
                                <figure><img src="{{ asset('images/rs1.png') }}" alt=""/></figure>
                                <div class="fig-right">
                                    <h5>Therapist</h5>
                                    <span>Mildred Wilson</span>
                                </div>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                        </li>
                        <li>
                            <div class="msg-top d-flex">
                                <figure><img src="{{ asset('images/rs3.png') }}" alt=""/></figure>
                                <div class="fig-right">
                                    <h5>Therapist</h5>
                                    <span>Mildred Wilson</span>
                                </div>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                        </li>
                        <li>
                            <div class="msg-top d-flex">
                                <figure><img src="{{ asset('images/rs4.png') }}" alt=""/></figure>
                                <div class="fig-right">
                                    <h5>Therapist</h5>
                                    <span>Mildred Wilson</span>
                                </div>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                        </li>
                        <li>
                            <div class="msg-top d-flex">
                                <figure><img src="{{ asset('images/rs2.png') }}" alt=""/></figure>
                                <div class="fig-right">
                                    <h5>Therapist</h5>
                                    <span>Mildred Wilson</span>
                                </div>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="load-strip">
                    <a href="#" class="javascript:void(0);">Load 3 previous messages</a>
                </div>
                <div class="msg-detail">
                    <div class="msg-detail-top d-flex justify-content-between">
                        <div class="mg-left">
                            <span>Mildred Wilson to Admin</span>Canceled Bookings
                        </div>
                        <div class="mg-right d-flex align-items-center">
                            <span>Just now</span>
                            <div class="mg-round"><img src="{{ asset('images/placeholder.png') }}" alt=""/></div>
                        </div>
                    </div>
                    <div class="msg-body">
                        <p>Hi Admin,<br>
                            <br>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Bibendum est ultricies integer quis. Iaculis urna id volutpat lacus laoreet.<br>
                            <br>
                            Tincidunt ornare massa eget egestas purus viverra accumsan in nisl. Tempor id eu nisl nunc mi ipsum faucibus. Fusce id velit ut tortor pretium. Massa ultricies mi quis hendrerit dolor magna eget.<br>
                            <br>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Bibendum est ultricies integer quis. Iaculis urna id volutpat lacus laoreet. Tincidunt ornare massa eget egestas purus viverra accumsan in nisl. Tempor id eu nisl nunc mi ipsum faucibus. Fusce id velit ut tortor pretium. Massa ultricies mi quis hendrerit dolor magna eget.
                        </p>
                    </div>
                    <div class="msb-bottom-sec">
                        <ul class="d-flex">
                            <li><a href="javascript:void(0);"><img src="{{ asset('images/m1.png') }}" alt=""/></a></li>
                            <li><a href="javascript:void(0);"><img src="{{ asset('images/m2.png') }}" alt=""/></a></li>
                            <li><a href="javascript:void(0);"><img src="{{ asset('images/m3.png') }}" alt=""/></a></li>
                            <li><a href="javascript:void(0);"><img src="{{ asset('images/m4.png') }}" alt=""/></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('right_content')
    <form action="{{ route('messages.index') }}" method="GET" id="search-form">
        <div class="msg-sidebar">
            <a class="new-massage" href="#">New Massage</a>
            <div class="msg-info">
                <ul>
                    <li class="inbox">
                        <div class="msg-icons"><img src="{{ asset('images/inbox.png') }}" alt=""/></div>
                        <label>Inbox</label><span class="note-count">7</span>
                    </li>
                    <li class="starred">
                        <div class="msg-icons"><img src="{{ asset('images/starred.png') }}" alt=""/></div>
                        <label>Starred</label>
                    </li>
                    <li class="drafts">
                        <div class="msg-icons"><img src="{{ asset('images/drafts.png') }}" alt=""/></div>
                        <label>Drafts</label><span class="note-count">2</span>
                    </li>
                    <li class="trash">
                        <div class="msg-icons"><img src="{{ asset('images/trash.png') }}" alt=""/></div>
                        <label>Trash</label>
                    </li>
                    <li class="archive">
                        <div class="msg-icons"><img src="{{ asset('images/archive.png') }}" alt=""/></div>
                        <label>Archive</label>
                    </li>
                    <li class="setting">
                        <div class="msg-icons"><img src="{{ asset('images/settings.png') }}" alt=""/></div>
                        <label>Settings</label>
                    </li>
                </ul>
            </div>
        </div>
    </form>
@endsection

@endsection
