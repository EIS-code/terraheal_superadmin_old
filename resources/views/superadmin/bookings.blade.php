@extends('superadmin.layouts.app')

@section('content')

<div class="content-sec wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.1s" id="list">
    <div class="center-top d-flex justify-content-between">
    <div class="top-right">
    </div>
</div>
<div class="booking-list">
    <div class="tab-pane" id="ct-reservations">
        <div class="title">Bookings</div>
            <div class="cnt-reservation">
                <div class="wht-bg d-flex justify-content-center">
                    <ul id="myTabs" class="nav nav-pills nav-justified ct-res" role="tablist" data-tabs="tabs">
                        <li><a href="#cancel" data-toggle="tab">Canceled</a></li>
                        <li><a href="#pending" data-toggle="tab">Pending</a></li>
                        <li><a href="#upcoming" data-toggle="tab">Upcoming</a></li>
                        <li><a href="#past" class="active" data-toggle="tab">Past</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <!-- <div role="tabpanel" class="tab-pane" id="cancel">
                        <div class="res-content">
                            <div class="res-top d-flex justify-content-between">
                                <div class="rs-date">30<span>Dec</span></div>
                                <div class="rs-center">
                                    <h2>Group Session</h2>
                                    <div class="rs-text">In Massage Center, <span>4:20Pm</span></div>
                                </div>
                                <div class="rs-last"> <a href="javascript:void(0);">Confirmed</a> </div>
                            </div>
                            <div class="res-bottom d-flex justify-content-between">
                                <ul class="d-flex">
                                    <li><img src="images/rs1.png" alt=""/></li>
                                    <li><img src="images/rs2.png" alt=""/></li>
                                    <li><img src="images/rs3.png" alt=""/></li>
                                    <li><img src="images/rs4.png" alt=""/></li>
                                    <li>+5</li>
                                </ul>
                                <a href="javascript:void(0);" class="btn-custom show" data-toggle="modal" data-target="#reservationmodel">Show</a> 
                            </div>
                        </div>
                    </div> -->
                    <div role="tabpanel" class="tab-pane" id="pending">
                        @if (!empty($bookingsPending))
                            @foreach ($bookingsPending as $bookingPending)
                                <div class="res-content">
                                    <div class="res-top d-flex justify-content-between">
                                        <div class="rs-date">{{ date('d', strtotime($bookingPending->massage_date)) }}<span>{{ date('M', strtotime($bookingPending->massage_date)) }}</span></div>
                                        <div class="rs-center">
                                            <h2>{{ $bookingPending->session_type }}</h2>
                                            <div class="rs-text">{{ $bookingPending->booking_type }}, <span>{{ !empty($bookingPending->massage_time) && strtotime($bookingPending->massage_time) ? date('H:ia', strtotime($bookingPending->massage_time)) : '' }}</span></div>
                                        </div>
                                        <div class="rs-last"> <a href="javascript:void(0);">Confirmed</a> </div>
                                    </div>
                                    <div class="res-bottom d-flex justify-content-between">
                                        <ul class="d-flex">
                                            @if (!empty($bookingPending->user_people))
                                                @foreach ($bookingPending->user_people as $userPeople)
                                                    <li><img src="{{ empty($userPeople->photo) ? asset('images/user-people.png') : $userPeople->photo }}" alt=""/></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <a href="javascript:void(0);" class="btn-custom show" data-toggle="modal" data-target="#reservationmodel-{{ $bookingPending->id }}">Show</a> 
                                    </div>
                                </div>

                                <div class="modal fade" id="reservationmodel-{{ $bookingPending->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header cust-head">
                                                <span class="code-id" data-toggle="modal" data-target="#codemodel"><img src="images/code.png" alt=""/></span>
                                                <h5 class="modal-title" id="exampleModalLongTitle">Booking ID: {{ $bookingPending->id }}</h5>
                                                <a href="#" class="btn-custom print">Print</a>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="model-inner no-pad">
                                                    <div class="booking-info">
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Center:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="Terra Heal Massage Center">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Date & Time:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="" placeholder="28 Oct 2020, 3:30pm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Delivery Types:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="In massage center">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Session Details:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="Group">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field txtarea">
                                                                <label>Delivery Address:</label>
                                                                <div class="grp-right">
                                                                    <textarea></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field txtarea">
                                                                <label>Booking Notes:</label>
                                                                <div class="grp-right">
                                                                    <textarea></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp full">
                                                            <div class="grp-field table-cont">
                                                                <label>Booking Notes:</label>
                                                                <div class="grp-right">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Sr.No</th>
                                                                                <th scope="col">Name</th>
                                                                                <th scope="col">Age</th>
                                                                                <th scope="col">Gender</th>
                                                                                <th scope="col">Pressure</th>
                                                                                <th scope="col">Services</th>
                                                                                <th scope="col">Durations</th>
                                                                                <th scope="col">Cost</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (!empty($bookingPending->user_people))
                                                                                @foreach ($bookingPending->user_people as $userPeople)
                                                                                    @if (!empty($userPeople->booking_massages))
                                                                                        @foreach ($userPeople->booking_massages as $index => $bookingMassages)
                                                                                            <tr>
                                                                                                <td>{{ $index + 1 }}</td>
                                                                                                <td>{{ $userPeople->name }}</td>
                                                                                                <td>{{ $userPeople->age }}</td>
                                                                                                <td>{{ $userPeople->gender == 'm' ? __('Male') : __('Female') }}</td>
                                                                                                <td>Soft</td>
                                                                                                <td>{{ $bookingMassages->name }}</td>
                                                                                                <td>{{ $bookingMassages->time }}</td>
                                                                                                <td>{{ $bookingMassages->price }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td colspan="6" class="no-border">&nbsp;</td>
                                                                                <td>Total</td>
                                                                                <td>{{ $bookingPending->total_price }}</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field table-cont">
                                                                <label>Payment Status:</label>
                                                                <div class="grp-right">
                                                                    <div class="norm-cont"> <span class="paid">-</span><small>-</small> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field table-cont">
                                                                <label>Payment Status:</label>
                                                                <div class="grp-right">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table payment">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Paid</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>voucher</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pack</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td>Pending</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="upcoming">
                        @if (!empty($bookingsUpcoming))
                            @foreach ($bookingsUpcoming as $bookingUpcoming)
                                <div class="res-content">
                                    <div class="res-top d-flex justify-content-between">
                                        <div class="rs-date">{{ date('d', strtotime($bookingUpcoming->massage_date)) }}<span>{{ date('M', strtotime($bookingUpcoming->massage_date)) }}</span></div>
                                        <div class="rs-center">
                                            <h2>{{ $bookingUpcoming->session_type }}</h2>
                                            <div class="rs-text">{{ $bookingUpcoming->booking_type }}, <span>{{ !empty($bookingUpcoming->massage_time) && strtotime($bookingUpcoming->massage_time) ? date('H:ia', strtotime($bookingUpcoming->massage_time)) : '' }}</span></div>
                                        </div>
                                        <div class="rs-last"> <a href="javascript:void(0);">Confirmed</a> </div>
                                    </div>
                                    <div class="res-bottom d-flex justify-content-between">
                                        <ul class="d-flex">
                                            @if (!empty($bookingUpcoming->user_people))
                                                @foreach ($bookingUpcoming->user_people as $userPeople)
                                                    <li><img src="{{ empty($userPeople->photo) ? asset('images/user-people.png') : $userPeople->photo }}" alt=""/></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <a href="javascript:void(0);" class="btn-custom show" data-toggle="modal" data-target="#reservationmodel-{{ $bookingUpcoming->id }}">Show</a> 
                                    </div>
                                </div>

                                <div class="modal fade" id="reservationmodel-{{ $bookingUpcoming->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header cust-head">
                                                <span class="code-id" data-toggle="modal" data-target="#codemodel"><img src="images/code.png" alt=""/></span>
                                                <h5 class="modal-title" id="exampleModalLongTitle">Booking ID: {{ $bookingUpcoming->id }}</h5>
                                                <a href="#" class="btn-custom print">Print</a>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="model-inner no-pad">
                                                    <div class="booking-info">
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Center:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="Terra Heal Massage Center">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Date & Time:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="" placeholder="28 Oct 2020, 3:30pm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Delivery Types:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="In massage center">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Session Details:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="Group">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field txtarea">
                                                                <label>Delivery Address:</label>
                                                                <div class="grp-right">
                                                                    <textarea></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field txtarea">
                                                                <label>Booking Notes:</label>
                                                                <div class="grp-right">
                                                                    <textarea></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp full">
                                                            <div class="grp-field table-cont">
                                                                <label>Booking Notes:</label>
                                                                <div class="grp-right">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Sr.No</th>
                                                                                <th scope="col">Name</th>
                                                                                <th scope="col">Age</th>
                                                                                <th scope="col">Gender</th>
                                                                                <th scope="col">Pressure</th>
                                                                                <th scope="col">Services</th>
                                                                                <th scope="col">Durations</th>
                                                                                <th scope="col">Cost</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (!empty($bookingUpcoming->user_people))
                                                                                @foreach ($bookingUpcoming->user_people as $userPeople)
                                                                                    @if (!empty($userPeople->booking_massages))
                                                                                        @foreach ($userPeople->booking_massages as $index => $bookingMassages)
                                                                                            <tr>
                                                                                                <td>{{ $index + 1 }}</td>
                                                                                                <td>{{ $userPeople->name }}</td>
                                                                                                <td>{{ $userPeople->age }}</td>
                                                                                                <td>{{ $userPeople->gender == 'm' ? __('Male') : __('Female') }}</td>
                                                                                                <td>Soft</td>
                                                                                                <td>{{ $bookingMassages->name }}</td>
                                                                                                <td>{{ $bookingMassages->time }}</td>
                                                                                                <td>{{ $bookingMassages->price }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td colspan="6" class="no-border">&nbsp;</td>
                                                                                <td>Total</td>
                                                                                <td>{{ $bookingUpcoming->total_price }}</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field table-cont">
                                                                <label>Payment Status:</label>
                                                                <div class="grp-right">
                                                                    <div class="norm-cont"> <span class="paid">-</span><small>-</small> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field table-cont">
                                                                <label>Payment Status:</label>
                                                                <div class="grp-right">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table payment">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Paid</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>voucher</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pack</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td>Pending</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane in active" id="past">
                        @if (!empty($bookingsPast))
                            @foreach ($bookingsPast as $bookingPast)
                                <div class="res-content">
                                    <div class="res-top d-flex justify-content-between">
                                        <div class="rs-date">{{ date('d', strtotime($bookingPast->massage_date)) }}<span>{{ date('M', strtotime($bookingPast->massage_date)) }}</span></div>
                                        <div class="rs-center">
                                            <h2>{{ $bookingPast->session_type }}</h2>
                                            <div class="rs-text">{{ $bookingPast->booking_type }}, <span>{{ !empty($bookingPast->massage_time) && strtotime($bookingPast->massage_time) ? date('H:ia', strtotime($bookingPast->massage_time)) : '' }}</span></div>
                                        </div>
                                        <div class="rs-last"> <a href="javascript:void(0);">Confirmed</a> </div>
                                    </div>
                                    <div class="res-bottom d-flex justify-content-between">
                                        <ul class="d-flex">
                                            @if (!empty($bookingPast->user_people))
                                                @foreach ($bookingPast->user_people as $userPeople)
                                                    <li><img src="{{ empty($userPeople->photo) ? asset('images/user-people.png') : $userPeople->photo }}" alt=""/></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <a href="javascript:void(0);" class="btn-custom show" data-toggle="modal" data-target="#reservationmodel-{{ $bookingPast->id }}">Show</a> 
                                    </div>
                                </div>

                                <div class="modal fade" id="reservationmodel-{{ $bookingPast->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header cust-head">
                                                <span class="code-id" data-toggle="modal" data-target="#codemodel"><img src="images/code.png" alt=""/></span>
                                                <h5 class="modal-title" id="exampleModalLongTitle">Booking ID: {{ $bookingPast->id }}</h5>
                                                <a href="#" class="btn-custom print">Print</a>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="model-inner no-pad">
                                                    <div class="booking-info">
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Center:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="Terra Heal Massage Center">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Date & Time:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="" placeholder="28 Oct 2020, 3:30pm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Delivery Types:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="In massage center">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field">
                                                                <label>Session Details:</label>
                                                                <div class="grp-right">
                                                                    <input type="text" name="center" placeholder="Group">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field txtarea">
                                                                <label>Delivery Address:</label>
                                                                <div class="grp-right">
                                                                    <textarea></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field txtarea">
                                                                <label>Booking Notes:</label>
                                                                <div class="grp-right">
                                                                    <textarea></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp full">
                                                            <div class="grp-field table-cont">
                                                                <label>Booking Notes:</label>
                                                                <div class="grp-right">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Sr.No</th>
                                                                                <th scope="col">Name</th>
                                                                                <th scope="col">Age</th>
                                                                                <th scope="col">Gender</th>
                                                                                <th scope="col">Pressure</th>
                                                                                <th scope="col">Services</th>
                                                                                <th scope="col">Durations</th>
                                                                                <th scope="col">Cost</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (!empty($bookingPast->user_people))
                                                                                @foreach ($bookingPast->user_people as $userPeople)
                                                                                    @if (!empty($userPeople->booking_massages))
                                                                                        @foreach ($userPeople->booking_massages as $index => $bookingMassages)
                                                                                            <tr>
                                                                                                <td>{{ $index + 1 }}</td>
                                                                                                <td>{{ $userPeople->name }}</td>
                                                                                                <td>{{ $userPeople->age }}</td>
                                                                                                <td>{{ $userPeople->gender == 'm' ? __('Male') : __('Female') }}</td>
                                                                                                <td>Soft</td>
                                                                                                <td>{{ $bookingMassages->name }}</td>
                                                                                                <td>{{ $bookingMassages->time }}</td>
                                                                                                <td>{{ $bookingMassages->price }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td colspan="6" class="no-border">&nbsp;</td>
                                                                                <td>Total</td>
                                                                                <td>{{ $bookingPast->total_price }}</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field table-cont">
                                                                <label>Payment Status:</label>
                                                                <div class="grp-right">
                                                                    <div class="norm-cont"> <span class="paid">-</span><small>-</small> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="book-grp half">
                                                            <div class="grp-field table-cont">
                                                                <label>Payment Status:</label>
                                                                <div class="grp-right">
                                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table payment">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Paid</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>voucher</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pack</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td>Pending</td>
                                                                                <td>-</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('right_content')
    <form action="{{ route('bookings.index') }}" method="GET" id="search-form">
        <div class="search-top center-search">
            <input type="text" placeholder="Search by Name, Email, Tel, DOB, NIF, QR code">
            <button type="submit"><img src="{{ asset('images/search.png') }}" alt="search"></button>
        </div>
        <div class="center-form">
            <div class="form-field">
                <label>Booking Types</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Booking Types</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Center</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Type of payment</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Booking for Centers</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
            <div class="form-field">
                <label>Booking for Home Visits</label>
                <select class="select-bg">
                    <option>Select...</option>
                    <option>Select 1</option>
                    <option>Select 2</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary font-white" onClick="window.location = window.location.href.split('?')[0];" type="button">{{ __('Clear') }}</button>
    </form>
@endsection

@endsection
