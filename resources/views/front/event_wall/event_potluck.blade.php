{{-- {{dd($potluckDetail)}} --}}
<main class="new-main-content">

    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <!-- =============mainleft-====================== -->
                <x-event_wall.wall_left_menu :page="$current_page" :eventDetails="$eventDetails" />
            </div>
            <div class="col-xl-6 col-lg-8">
                <div class="main-content-center">
                    <!-- ===event-breadcrumb-wrp-start=== -->
                    <div class="event-breadcrumb-wrp">
                        <nav style="
                    --bs-breadcrumb-divider: url(
                      &#34;data:image/svg + xml,
                      %3Csvgxmlns='http://www.w3.org/2000/svg'width='8'height='8'%3E%3Cpathd='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z'fill='%236c757d'/%3E%3C/svg%3E&#34;
                    );
                  "
                            aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Events</a></li>
                                <li class="breadcrumb-item">
                                    <a
                                        href="{{ route('event.event_wall', encrypt($eventDetails['id'])) }}">{{ $eventDetails['event_name'] }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    potluck
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- ===event-breadcrumb-wrp-end=== -->
                    <x-event_wall.wall_title :eventDetails="$eventDetails" />
                    <!-- ===event-center-title-start=== -->

                    <!-- ===event-center-title-end=== -->

                    <!-- ===event-center-tabs-main-start=== -->
                    <div class="event-center-tabs-main">
                        {{-- {{dd($current_page)}} --}}
                        <!-- ====================navbar-============================= -->
                        <x-event_wall.wall_navbar :event="$event" :page="$current_page" :eventDetails="$eventDetails" />

                        <!-- ===tab-content-start=== -->
                        <div class="tab-content" id="nav-tabContent">
                            @php
                                $potluck_active = '';
                                $potluck_show = '';
                                if ($current_page == 'potluck') {
                                    $potluck_active = 'active';
                                    $potluck_show = 'show';
                                }
                            @endphp
                            <div class="tab-pane fade {{ $potluck_active }} {{ $potluck_show }}" id="nav-potluck"
                                role="tabpanel" aria-labelledby="nav-potluck-tab">
                                <div class="potuck-main-wrp">
                                    <div class="summer-progress cmn-card">
                                        <div id="chartData" data-spoken_for="{{ $potluckDetail['spoken_for'] }} "
                                            data-potluck-item="{{ $potluckDetail['potluck_items'] }}"
                                            data-missing-still="{{ $potluckDetail['left'] }}">
                                        </div>
                                        <div id="chart" class=""></div>
                                    </div>
                                    <div class="total-item-cat cmn-card">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                                <div class="item-cards">
                                                    <div class="item-value">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M3.14815 1.87598C3.14815 1.59983 3.37201 1.37598 3.64815 1.37598H10.4489C10.725 1.37598 10.9489 1.59983 10.9489 1.87598V3.59609L12.2612 6.58905C12.2889 6.65235 12.3032 6.72071 12.3032 6.78983V12.2304C12.3032 12.5066 12.0794 12.7304 11.8032 12.7304H9.00776H2.19722C1.92108 12.7304 1.69722 12.5066 1.69722 12.2304V10.782V10.282H2.69722V10.782V11.7304H8.50776V6.78983C8.50776 6.72071 8.52209 6.65235 8.54985 6.58905L9.64032 4.10198H3.92168L2.69722 6.89463V6.89499V7.39499H1.69722V6.89499V6.78983C1.69722 6.72071 1.71155 6.65235 1.7393 6.58905L3.13704 3.4012C3.14055 3.39319 3.14426 3.38531 3.14815 3.37756V1.87598ZM4.14815 2.37598V3.10197H9.94887V2.37598H4.14815ZM10.4055 4.84714L9.50776 6.89463V11.7304H11.3032V6.89463L10.4055 4.84714ZM6.2703 7.85355L6.62385 7.5L5.91674 6.79289L5.56319 7.14645L3.45841 9.25123L2.35363 8.14645L2.00008 7.79289L1.29297 8.5L1.64652 8.85355L3.10486 10.3119C3.19862 10.4057 3.3258 10.4583 3.45841 10.4583C3.59102 10.4583 3.71819 10.4057 3.81196 10.3119L6.2703 7.85355Z"
                                                                fill="#F73C71" />
                                                        </svg>
                                                        <h6>Total Items</h6>
                                                    </div>
                                                    <h3>{{ $potluckDetail['item'] }}</h3>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                                <div class="item-cards">
                                                    <div class="item-value">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M6.48682 10.6459C6.37599 10.6459 6.26516 10.605 6.17766 10.5175L5.30266 9.64252C5.13349 9.47335 5.13349 9.19335 5.30266 9.02419C5.47182 8.85502 5.75182 8.85502 5.92099 9.02419L6.49849 9.60169L8.08516 8.13752C8.26016 7.97419 8.54016 7.98585 8.70349 8.16085C8.86682 8.33585 8.85516 8.61585 8.68016 8.77919L6.78432 10.5292C6.70266 10.605 6.59766 10.6459 6.48682 10.6459Z"
                                                                fill="#F73C71" />
                                                            <path
                                                                d="M3.02589 3.72151C2.91505 3.72151 2.79839 3.67484 2.71672 3.59318C2.54755 3.42401 2.54755 3.14401 2.71672 2.97484L4.83422 0.857344C5.00339 0.688177 5.28339 0.688177 5.45255 0.857344C5.62172 1.02651 5.62172 1.30651 5.45255 1.47568L3.33505 3.59318C3.24755 3.67484 3.13672 3.72151 3.02589 3.72151Z"
                                                                fill="#F73C71" />
                                                            <path
                                                                d="M10.9715 3.72151C10.8607 3.72151 10.7498 3.68068 10.6623 3.59318L8.54484 1.47568C8.37568 1.30651 8.37568 1.02651 8.54484 0.857344C8.71401 0.688177 8.99401 0.688177 9.16318 0.857344L11.2807 2.97484C11.4498 3.14401 11.4498 3.42401 11.2807 3.59318C11.199 3.67484 11.0823 3.72151 10.9715 3.72151Z"
                                                                fill="#F73C71" />
                                                            <path
                                                                d="M11.7905 6.18343C11.7496 6.18343 11.7088 6.18343 11.668 6.18343H11.5338H2.33464C1.9263 6.18926 1.45964 6.18926 1.1213 5.85093C0.852969 5.58843 0.730469 5.1801 0.730469 4.57926C0.730469 2.9751 1.90297 2.9751 2.46297 2.9751H11.5396C12.0996 2.9751 13.2721 2.9751 13.2721 4.57926C13.2721 5.18593 13.1496 5.58843 12.8813 5.85093C12.578 6.15426 12.1696 6.18343 11.7905 6.18343ZM2.46297 5.30843H11.6738C11.9363 5.31426 12.1813 5.31426 12.263 5.2326C12.3038 5.19176 12.3913 5.05176 12.3913 4.57926C12.3913 3.9201 12.228 3.8501 11.5338 3.8501H2.46297C1.7688 3.8501 1.60547 3.9201 1.60547 4.57926C1.60547 5.05176 1.6988 5.19176 1.7338 5.2326C1.81547 5.30843 2.0663 5.30843 2.32297 5.30843H2.46297Z"
                                                                fill="#F73C71" />
                                                            <path
                                                                d="M8.68756 13.2709H5.17006C3.08173 13.2709 2.61506 12.0284 2.43423 10.9493L1.61173 5.90343C1.5709 5.66427 1.73423 5.4426 1.9734 5.40177C2.20673 5.36093 2.43423 5.52427 2.47506 5.76343L3.29756 10.8034C3.46673 11.8359 3.81673 12.3959 5.17006 12.3959H8.68756C10.1867 12.3959 10.3559 11.8709 10.5484 10.8559L11.5284 5.75177C11.5751 5.5126 11.8026 5.3551 12.0417 5.4076C12.2809 5.45427 12.4326 5.68177 12.3859 5.92093L11.4059 11.0251C11.1784 12.2093 10.7992 13.2709 8.68756 13.2709Z"
                                                                fill="#F73C71" />
                                                        </svg>
                                                        <h6>Categories</h6>
                                                    </div>
                                                    <h3>{{ $potluckDetail['total_potluck_categories'] }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (!empty($potluckDetail['item_summary']))
                                        <div class="summary-item cmn-card">
                                            <h5 class="title">Item Summary</h5>
                                            <div class="summary-item-data">
                                                @foreach ($potluckDetail['item_summary'] as $summary)
                                                    <div class="summary-item-con">
                                                        <h6>{{ $summary['category'] }}</h6>
                                                        <span>{{ $summary['spoken_items'] }}/{{ $summary['total_items'] }}</span>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    @endif
                                    @if ($eventDetails['hosted_by'])
                                        <div class="massage-host cmn-card">
                                            <h5 class="title">Message From Host</h5>
                                            <div class="massge-data">
                                                <div class="host-img">
                                                    @if ($eventDetails['user_profile'] != '')
                                                    <img src="{{ $eventDetails['user_profile'] }}" alt="host-img" loading="lazy">
                                                    @else
                                                        @php

                                                            // $parts = explode(" ", $name);
                                                            $nameParts = explode(' ', $eventDetails['hosted_by'] );

                                                            $firstInitial = isset($nameParts[0][0])
                                                                ? strtoupper($nameParts[0][0])
                                                                : '';
                                                            $secondInitial = isset($nameParts[1][0])
                                                                ? strtoupper($nameParts[1][0])
                                                                : '';
                                                            $initials = $firstInitial . $secondInitial;

                                                            // Generate a font color class based on the first initial
                                                            $fontColor = 'fontcolor' . $firstInitial;
                                                        @endphp
                                                        <h5 class="{{ $fontColor }}">
                                                            {{ $initials }}
                                                        </h5>
                                                    @endif

                                                </div>
                                                <h5>{{ $eventDetails['hosted_by'] }}</h5>
                                                <h6>Host</h6>
                                                <a href="#">Message</a>
                                                <p
                                                    style="{{ empty($eventDetails['message_to_guests']) ? 'display: none;' : '' }}">
                                                    “{{ $eventDetails['message_to_guests'] }}”
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- {{   dd(  $potluckDetail['podluck_category_list'])}} --}}
                                    <div class="post-potluck-category cmn-card">
                                        <div class="d-flex align-items-center">
                                            <h5 class="title">Potluck Categories</h5>
                                            <button type="button" class="ms-auto border-0" data-bs-toggle="modal"
                                                data-bs-target="#editmodal" style="background-color: transparent; box-shadow: none;">
                                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z"
                                                        fill="#F73C71" />
                                                </svg>
                                            </button>
                                        </div>
                                        {{-- {{  dd($potluckDetail['podluck_category_list'])}} --}}
                                        @foreach ($potluckDetail['podluck_category_list'] as $category)
                                        <div class="category-main-dishesh">


                                                @php
                                                    $total_item_quantity = 0;
                                                    $total_missing_quantity = 0;
                                                    $over_quantity_count = 0;
                                                    // Sum up the quantities of all items in the category
                                                    foreach ($category['items'] as $item) {
                                                        $total_item_quantity += $item['quantity'];
                                                        $missing_quantity =
                                                            $item['quantity'] - $item['spoken_quantity'];
                                                        if ($missing_quantity > 0) {
                                                            $total_missing_quantity += $missing_quantity;
                                                        }
                                                        if ($item['spoken_quantity'] > $item['quantity']) {
                                                            $over_quantity_count++;
                                                        }
                                                        $button_disabled =
                                                            $total_item_quantity == $total_missing_quantity
                                                                ? 'disabled'
                                                                : '';
                                                } @endphp
                                                <div class="category-list" id="sublist"
                                                    data-category-id="{{ $category['id'] }}"
                                                    data-total-quantity="{{ $total_item_quantity }}">
                                                    <div class="list-header">
                                                        <span
                                                            class="me-1 list-sub-head">{{ $total_item_quantity }}</span>
                                                        <div>
                                                            <h5>{{ $category['category'] }}</h5>
                                                        </div>
                                                        <input type="hidden" id="category_id"
                                                            name="event_potluck_category_id"
                                                            value="{{ $category['id'] }}">
                                                        <div class="ms-auto d-flex align-items-center ">
                                                            {{-- @if ($total_missing_quantity == 0) --}}
                                                            @php
                                                                $display_icon = 'd-none';
                                                                $hide_button = '';
                                                                $missing = '';
                                                                if ($total_missing_quantity == 0) {
                                                                    $display_icon = '';
                                                                    $missing = 'color:red';
                                                                    $hide_button = 'd-none'; // Hides the button
                                                                }
                                                            @endphp
                                                            <span id ="success_{{ $category['id'] }}"
                                                                class="me-2 d-flex align-items-center justify-content-center {{ $display_icon }}">
                                                                <svg width="20" height="20" viewBox="0 0 18 18"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                        fill="#23AA26" />
                                                                </svg>
                                                            </span>
                                                            <span id ="danger_{{ $category['id'] }}"
                                                                class="me-2 d-flex align-items-center justify-content-center  {{ $display_icon == 'd-none' ? '' : 'd-none' }}">
                                                                <svg width="20" height="20"
                                                                    viewBox="0 0 14 14" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z"
                                                                        fill="#F73C71" />
                                                                </svg>
                                                            </span>
                                                            <h6 class="me-3 missing-quantity "
                                                                style="{{ $missing }}">
                                                                {{ $total_missing_quantity }} Missing</h6>
                                                            <span id ="success_{{ $category['id'] }}"
                                                                class="me-2 d-flex align-items-center justify-content-center d-none">
                                                                <svg width="20" height="20"
                                                                    viewBox="0 0 18 18" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                        fill="#23AA26" />
                                                                </svg>
                                                            </span>
                                                            <h6 class="me-3 over-quantity d-none"
                                                                style="color:green;">
                                                                {{ $over_quantity_count }}
                                                                Item Over</h6>
                                                            @php
                                                                foreach ($category['items'] as $item) {
                                                                    $isDisabled =
                                                                        $item['spoken_quantity'] ==
                                                                        $category['quantity'];
                                                                }
                                                            @endphp
                                                            <button type="button" class="me-3 "
                                                                data-bs-toggle="modal" data-bs-target="#maindishes" style="background-color: transparent; box-shadow: none;"
                                                                data-category-id="{{ $category['id'] }}"
                                                                data-category-name="{{ $category['category'] }}">
                                                                <svg width="22" height="22"
                                                                    viewBox="0 0 22 22" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z"
                                                                        fill="#F73C71" />
                                                                </svg>
                                                            </button>
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton1"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <svg width="20" height="20"
                                                                        viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11.6665 4.16667C11.6665 3.25 10.9165 2.5 9.99984 2.5C9.08317 2.5 8.33317 3.25 8.33317 4.16667C8.33317 5.08333 9.08317 5.83333 9.99984 5.83333C10.9165 5.83333 11.6665 5.08333 11.6665 4.16667Z"
                                                                            fill="#0F172A"></path>
                                                                        <path
                                                                            d="M11.6665 15.8327C11.6665 14.916 10.9165 14.166 9.99984 14.166C9.08317 14.166 8.33317 14.916 8.33317 15.8327C8.33317 16.7493 9.08317 17.4993 9.99984 17.4993C10.9165 17.4993 11.6665 16.7493 11.6665 15.8327Z"
                                                                            fill="#0F172A"></path>
                                                                        <path
                                                                            d="M11.6665 10.0007C11.6665 9.08398 10.9165 8.33398 9.99984 8.33398C9.08317 8.33398 8.33317 9.08398 8.33317 10.0007C8.33317 10.9173 9.08317 11.6673 9.99984 11.6673C10.9165 11.6673 11.6665 10.9173 11.6665 10.0007Z"
                                                                            fill="#0F172A"></path>
                                                                    </svg>
                                                                </a>
                                                                <ul class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton1">
                                                                    <li>
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editcategorymodal"
                                                                            data-category-id="{{ $category['id'] }}"
                                                                            data-category-name="{{ $category['category'] }}"
                                                                            data-category-quantity="{{ $category['quantity'] }}">
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="me-2">
                                                                                    <svg width="20" height="20"
                                                                                        viewBox="0 0 20 20"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M12.4993 18.9577L7.49935 18.9577C2.97435 18.9577 1.04102 17.0243 1.04102 12.4993L1.04102 7.49935C1.04102 2.97435 2.97435 1.04102 7.49935 1.04102L9.16602 1.04102C9.50768 1.04102 9.79102 1.32435 9.79102 1.66602C9.79102 2.00768 9.50768 2.29102 9.16602 2.29102L7.49935 2.29102C3.65768 2.29102 2.29102 3.65768 2.29102 7.49935L2.29102 12.4993C2.29102 16.341 3.65768 17.7077 7.49935 17.7077L12.4993 17.7077C16.341 17.7077 17.7077 16.341 17.7077 12.4993L17.7077 10.8327C17.7077 10.491 17.991 10.2077 18.3327 10.2077C18.6743 10.2077 18.9577 10.491 18.9577 10.8327L18.9577 12.4993C18.9577 17.0243 17.0243 18.9577 12.4993 18.9577Z"
                                                                                            fill="#94A3B8" />
                                                                                        <path
                                                                                            d="M7.08409 14.7424C6.57576 14.7424 6.10909 14.5591 5.76742 14.2258C5.35909 13.8174 5.18409 13.2258 5.27576 12.6008L5.63409 10.0924C5.70076 9.60911 6.01742 8.98411 6.35909 8.64245L12.9258 2.07578C14.5841 0.417448 16.2674 0.417448 17.9258 2.07578C18.8341 2.98411 19.2424 3.90911 19.1591 4.83411C19.0841 5.58411 18.6841 6.31745 17.9258 7.06745L11.3591 13.6341C11.0174 13.9758 10.3924 14.2924 9.90909 14.3591L7.40076 14.7174C7.29242 14.7424 7.18409 14.7424 7.08409 14.7424ZM13.8091 2.95911L7.24242 9.52578C7.08409 9.68411 6.90076 10.0508 6.86742 10.2674L6.50909 12.7758C6.47576 13.0174 6.52576 13.2174 6.65076 13.3424C6.77576 13.4674 6.97576 13.5174 7.21742 13.4841L9.72576 13.1258C9.94242 13.0924 10.3174 12.9091 10.4674 12.7508L17.0341 6.18411C17.5758 5.64245 17.8591 5.15911 17.9008 4.70911C17.9508 4.16745 17.6674 3.59245 17.0341 2.95078C15.7008 1.61745 14.7841 1.99245 13.8091 2.95911Z"
                                                                                            fill="#94A3B8" />
                                                                                        <path
                                                                                            d="M16.5423 8.19124C16.484 8.19124 16.4256 8.18291 16.3756 8.16624C14.1839 7.54957 12.4423 5.80791 11.8256 3.61624C11.7339 3.28291 11.9256 2.94124 12.259 2.84124C12.5923 2.74957 12.934 2.94124 13.0256 3.27457C13.5256 5.04957 14.9339 6.45791 16.7089 6.95791C17.0423 7.04957 17.2339 7.39957 17.1423 7.73291C17.0673 8.01624 16.8173 8.19124 16.5423 8.19124Z"
                                                                                            fill="#94A3B8" />
                                                                                    </svg>
                                                                                </span>
                                                                                <h6>Edit</h6>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-category-id="{{ $category['id'] }}"
                                                                            data-event-id="{{ $event }}"
                                                                            data-bs-target="#deletemodal">
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="me-2">
                                                                                    <svg width="16" height="16"
                                                                                        viewBox="0 0 16 16"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M5.66602 3.31398L5.81268 2.44065C5.91935 1.80732 5.99935 1.33398 7.12602 1.33398H8.87268C9.99935 1.33398 10.086 1.83398 10.186 2.44732L10.3327 3.31398"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M12.5669 6.09375L12.1336 12.8071C12.0603 13.8537 12.0003 14.6671 10.1403 14.6671H5.86026C4.00026 14.6671 3.94026 13.8537 3.86693 12.8071L3.43359 6.09375"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path d="M6.88672 11H9.10672"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M6.33398 8.33398H9.66732"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                    </svg>

                                                                                </span>
                                                                                <h6>Delete
                                                                                </h6>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="list-created">Created by:
                                                        {{ $category['created_by'] }}</span>
                                                    <div class="list-body d-flex align-items-center">
                                                        <span
                                                            class="me-2 d-flex align-items-center justify-content-center">
                                                            <svg width="19" height="19" viewBox="0 0 14 15"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M6.99984 14.166C10.6732 14.166 13.6665 11.1727 13.6665 7.49935C13.6665 3.82602 10.6732 0.832682 6.99984 0.832682C3.3265 0.832682 0.33317 3.82602 0.33317 7.49935C0.33317 11.1727 3.3265 14.166 6.99984 14.166ZM7.49984 10.166C7.49984 10.4393 7.27317 10.666 6.99984 10.666C6.7265 10.666 6.49984 10.4393 6.49984 10.166V6.83268C6.49984 6.55935 6.7265 6.33268 6.99984 6.33268C7.27317 6.33268 7.49984 6.55935 7.49984 6.83268V10.166ZM6.3865 4.57935C6.41984 4.49268 6.4665 4.42602 6.5265 4.35935C6.59317 4.29935 6.6665 4.25268 6.7465 4.21935C6.8265 4.18602 6.91317 4.16602 6.99984 4.16602C7.0865 4.16602 7.17317 4.18602 7.25317 4.21935C7.33317 4.25268 7.4065 4.29935 7.47317 4.35935C7.53317 4.42602 7.57984 4.49268 7.61317 4.57935C7.6465 4.65935 7.6665 4.74602 7.6665 4.83268C7.6665 4.91935 7.6465 5.00602 7.61317 5.08602C7.57984 5.16602 7.53317 5.23935 7.47317 5.30602C7.4065 5.36602 7.33317 5.41268 7.25317 5.44602C7.09317 5.51268 6.9065 5.51268 6.7465 5.44602C6.6665 5.41268 6.59317 5.36602 6.5265 5.30602C6.4665 5.23935 6.41984 5.16602 6.3865 5.08602C6.35317 5.00602 6.33317 4.91935 6.33317 4.83268C6.33317 4.74602 6.35317 4.65935 6.3865 4.57935Z"
                                                                    fill="#FD5983" />
                                                            </svg>
                                                        </span>
                                                        <p>Nobody has added anything yet</h5>
                                                    </div>
                                                    {{-- {{ dd($category['items'])}} --}}
                                                    <div class="list-slide">
                                                        <div class="accordion accordion-flush" id="accordioncatList">
                                                            @foreach ($category['items'] as $item)
                                                                <div class="accordion-item active">
                                                                    <input type="hidden" id="category_item_id"
                                                                        name="event_potluck_category_item_id"
                                                                        value="{{ $item['id'] }}">
                                                                    <h2 class="accordion-header" id="sprite-{{ $item['id'] }}">
                                                                        <button class="accordion-btn accordion-button">
                                                                            <div class="d-flex align-items-center">
                                                                                @php
                                                                                    $icons = 'd-none';
                                                                                    $missing = '';
                                                                                    if (
                                                                                        $item['spoken_quantity'] ==
                                                                                        $item['quantity']
                                                                                    ) {
                                                                                        $icons = '';
                                                                                        $missing = 'color:green';
                                                                                    }
                                                                                @endphp
                                                                                <span
                                                                                    id ="success_{{ $item['id'] }}"
                                                                                    class="me-2 d-flex align-items-center justify-content-center {{ $icons }}">
                                                                                    <svg width="20" height="20"
                                                                                        viewBox="0 0 18 18"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                                            fill="#23AA26" />
                                                                                    </svg>
                                                                                </span>

                                                                                <span id ="danger_{{ $item['id'] }}"
                                                                                    class="me-2 d-flex align-items-center justify-content-center {{ $icons == 'd-none' ? '' : 'd-none' }}">
                                                                                    <svg width="20" height="20"
                                                                                        viewBox="0 0 14 14"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z"
                                                                                            fill="#F73C71" />
                                                                                    </svg>
                                                                                </span>

                                                                                <div class="d-flex flex-column">
                                                                                    <h5> {{ $item['description'] }}
                                                                                    </h5>
                                                                                    <span
                                                                                        class="list-created">Requested
                                                                                        by:
                                                                                        {{ $item['requested_by'] }}</span>
                                                                                    @if ($item['is_host'] == '1')
                                                                                        <span
                                                                                            class="host">Host</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="ms-auto d-flex">
                                                                                <h6 class="devide-count"
                                                                                    id="quantity-display">
                                                                                    {{ $item['spoken_quantity'] }}/{{ $item['quantity'] }}
                                                                                </h6>


                                                                                <span
                                                                                    class="accordion-button plus_icon_user collapsed"
                                                                                    data-category-id="{{ $category['id'] }}"
                                                                                    data-item-id="{{ $item['id'] }}"
                                                                                    data-max="{{ $item['quantity'] }}"
                                                                                    data-event-id="{{ $event }}"
                                                                                    data-login-user-id={{ $login_user_id }}
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#sprite-collapseOne-{{ $item['id'] }}"
                                                                                    aria-expanded="false"
                                                                                    aria-controls="sprite-collapseOne-{{ $item['id'] }}">   <i
                                                                                        class="fa-solid fa-plus"></i></span>
                                                                            </div>
                                                                        </button>
                                                                    </h2>

                                                                    @foreach ($item['item_carry_users'] as $users)
                                                                        @if ($login_user_id === $users['user_id'])
                                                                            <div id="sprite-collapseOne-{{ $item['id'] }}"
                                                                                class="accordion-collapse collapse @if (collect($item['item_carry_users'])->contains('user_id', $login_user_id)) show @endif"
                                                                                aria-labelledby="sprite-{{ $item['id'] }}"
                                                                                data-bs-parent="#accordionFlushExample">
                                                                                <div class="accordion-body">
                                                                                    {{-- {{ dd($item['item_carry_users'])}} --}}

                                                                                    <div
                                                                                        class="accordion-body-content limits-count">
                                                                                        @if ($users['profile'] != '')
                                                                                            <img src="{{ $users['profile'] }}"
                                                                                                alt="profile">
                                                                                        @else
                                                                                            @php
                                                                                                $name =
                                                                                                    $users[
                                                                                                        'first_name'
                                                                                                    ];
                                                                                                // $parts = explode(" ", $name);
                                                                                                $firstInitial = isset(
                                                                                                    $users[
                                                                                                        'first_name'
                                                                                                    ][0],
                                                                                                )
                                                                                                    ? strtoupper(
                                                                                                        $users[
                                                                                                            'first_name'
                                                                                                        ][0][0],
                                                                                                    )
                                                                                                    : '';
                                                                                                $secondInitial = isset(
                                                                                                    $users[
                                                                                                        'last_name'
                                                                                                    ][0],
                                                                                                )
                                                                                                    ? strtoupper(
                                                                                                        $users[
                                                                                                            'last_name'
                                                                                                        ][0][0],
                                                                                                    )
                                                                                                    : '';
                                                                                                $initials =
                                                                                                    strtoupper(
                                                                                                        $firstInitial,
                                                                                                    ) .
                                                                                                    strtoupper(
                                                                                                        $secondInitial,
                                                                                                    );
                                                                                                $fontColor =
                                                                                                    'fontcolor' .
                                                                                                    strtoupper(
                                                                                                        $firstInitial,
                                                                                                    );
                                                                                            @endphp
                                                                                            <h5
                                                                                                class="{{ $fontColor }} slide-user-img">
                                                                                                {{ $initials }}
                                                                                            </h5>
                                                                                        @endif

                                                                                        <h5>{{ $users['first_name'] }}
                                                                                            {{ $users['last_name'] }}
                                                                                        </h5>
                                                                                        <div
                                                                                            class="qty-container qty-custom ms-auto">
                                                                                            <button
                                                                                                class="minus m-0"data-category-id="{{ $category['id'] }}"
                                                                                                data-item-id="{{ $item['id'] }}"
                                                                                                type="button"><i
                                                                                                    class="fa fa-minus "></i></button>
                                                                                            {{-- <input type="hidden"

                                                                                                value="{{ $item['quantity'] }}" /> --}}
                                                                                            <input type="number"
                                                                                                id="newQuantity_{{ $item['id'] }}"
                                                                                                name="qty"
                                                                                                value="{{ $users['quantity'] }}"
                                                                                                class="input-qty itemQty"
                                                                                                data-max="{{ $item['quantity'] }}"
                                                                                                data-item-id="{{ $item['id'] }}"
                                                                                                data-spoken-quantity="{{ $item['spoken_quantity'] }}" />
                                                                                            {{-- <button class="qty-btn-plus plus-potluck-item"
                                                                                            type="button"><i
                                                                                                class="fa fa-plus"></i></button> --}}
                                                                                            <button class="plus"
                                                                                                data-category-id="{{ $category['id'] }}"
                                                                                                data-item-id="{{ $item['id'] }}"
                                                                                                type="button"><i
                                                                                                    class="fa fa-plus"></i></button>
                                                                                        </div>
                                                                                        <div class="d-flex">
                                                                                            <button type="button"
                                                                                                data-category-id="{{ $category['id'] }}"
                                                                                                data-item-id="{{ $item['id'] }}"
                                                                                                class="saveItemBtn me-3 d-flex align-items-center justify-content-center edit-modal-btn">
                                                                                                <svg width="16"
                                                                                                    height="16"
                                                                                                    viewBox="0 0 16 16"
                                                                                                    fill="none"
                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                    <path
                                                                                                        d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                                                                                        stroke="#94A3B8"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-miterlimit="10"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                    <path
                                                                                                        d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255"
                                                                                                        stroke="#94A3B8"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-miterlimit="10"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                </svg>
                                                                                            </button>
                                                                                            <button type="button"
                                                                                                data-category-id="{{ $category['id'] }}"
                                                                                                data-item-id="{{ $item['id'] }}"
                                                                                                data-event-id="{{ $event }}"
                                                                                                class="delete-modal-btn deleteBtn">
                                                                                                <svg width="16"
                                                                                                    height="16"
                                                                                                    viewBox="0 0 16 16"
                                                                                                    fill="none"
                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                    <path
                                                                                                        d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763"
                                                                                                        stroke="#F73C71"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                    <path
                                                                                                        d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398"
                                                                                                        stroke="#F73C71"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                    <path
                                                                                                        d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375"
                                                                                                        stroke="#F73C71"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                    <path
                                                                                                        d="M6.88666 11H9.10666"
                                                                                                        stroke="#F73C71"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                    <path
                                                                                                        d="M6.33331 8.33398H9.66665"
                                                                                                        stroke="#F73C71"
                                                                                                        stroke-width="1.5"
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round" />
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        @else

                                                                            <div id="lumpia-collapseOne"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="lumpia"
                                                                                data-bs-parent="#accordionFlushExample">
                                                                                <div class="accordion-body"
                                                                                    id="user-container-{{ $item['id'] }}">
                                                                                    <div
                                                                                        class="accordion-body-content">
                                                                                        @if ($users['profile'] != '')
                                                                                            <img class="slide-user-img"
                                                                                                src="{{ $users['profile'] }}"
                                                                                                alt="pofile">
                                                                                        @else
                                                                                            @php
                                                                                                $name =
                                                                                                    $users[
                                                                                                        'first_name'
                                                                                                    ];
                                                                                                // $parts = explode(" ", $name);
                                                                                                $firstInitial = isset(
                                                                                                    $users[
                                                                                                        'first_name'
                                                                                                    ][0],
                                                                                                )
                                                                                                    ? strtoupper(
                                                                                                        $users[
                                                                                                            'first_name'
                                                                                                        ][0][0],
                                                                                                    )
                                                                                                    : '';
                                                                                                $secondInitial = isset(
                                                                                                    $users[
                                                                                                        'last_name'
                                                                                                    ][0],
                                                                                                )
                                                                                                    ? strtoupper(
                                                                                                        $users[
                                                                                                            'last_name'
                                                                                                        ][0][0],
                                                                                                    )
                                                                                                    : '';
                                                                                                $initials =
                                                                                                    strtoupper(
                                                                                                        $firstInitial,
                                                                                                    ) .
                                                                                                    strtoupper(
                                                                                                        $secondInitial,
                                                                                                    );
                                                                                                $fontColor =
                                                                                                    'fontcolor' .
                                                                                                    strtoupper(
                                                                                                        $firstInitial,
                                                                                                    );
                                                                                            @endphp
                                                                                            <h5
                                                                                                class="{{ $fontColor }}">
                                                                                                {{ $initials }}
                                                                                            </h5>
                                                                                        @endif
                                                                                        <h5 class="slide-sub">
                                                                                            {{ $users['first_name'] }}
                                                                                            {{ $users['last_name'] }}
                                                                                        </h5>
                                                                                        <span
                                                                                            class="ms-auto slide-round">{{ $item['spoken_quantity'] }}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endforeach




                                                            {{-- <div class="accordion-item active">
                                                                    <h2 class="accordion-header" id="root">
                                                                        <button class="accordion-btn accordion-button">
                                                                            <div class="d-flex align-items-center">
                                                                                <span
                                                                                    class="me-2 d-flex align-items-center justify-content-center">
                                                                                    <svg width="20" height="20"
                                                                                        viewBox="0 0 18 18" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                                            fill="#23AA26" />
                                                                                    </svg>
                                                                                </span>
                                                                                <div>
                                                                                    <h5>Root Beer</h5>
                                                                                    <span class="list-created">Requested
                                                                                        by: Oscar Hernandez</span>
                                                                                    <span class="host">Host</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="ms-auto d-flex">
                                                                                <h6 class="devide-count">3/3</h6>
                                                                                <span class="accordion-button collapsed"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#root-collapseTwo"
                                                                                    aria-expanded="false"
                                                                                    aria-controls="root-collapseTwo"><i
                                                                                        class="fa-solid fa-plus"></i></span>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="root-collapseTwo"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="root"
                                                                        data-bs-parent="#accordionFlushExample">
                                                                        <div class="accordion-body">
                                                                            <div
                                                                                class="accordion-body-content limits-count">
                                                                                <img src="{{ asset('assets/front/img/header-profi') }}le-img.png"
                                                                                    alt="profile">
                                                                                <h5>Pristia Candra</h5>
                                                                                <div
                                                                                    class="qty-container qty-custom ms-auto">
                                                                                    <button class="qty-btn-minus"
                                                                                        type="button"><i
                                                                                            class="fa fa-minus"></i></button>
                                                                                    <input type="number" name="qty"
                                                                                        value="0"
                                                                                        class="input-qty" />
                                                                                    <button class="qty-btn-plus"
                                                                                        type="button"><i
                                                                                            class="fa fa-plus"></i></button>
                                                                                </div>
                                                                                <div class="d-flex">
                                                                                    <button type="button"
                                                                                        class="me-3 d-flex align-items-center justify-content-center edit-modal-btn">
                                                                                        <svg width="16" height="16"
                                                                                            viewBox="0 0 16 16"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                                                                                stroke="#94A3B8"
                                                                                                stroke-width="1.5"
                                                                                                stroke-miterlimit="10"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path
                                                                                                d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255"
                                                                                                stroke="#94A3B8"
                                                                                                stroke-width="1.5"
                                                                                                stroke-miterlimit="10"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                        </svg>
                                                                                    </button>
                                                                                    <button type="button"
                                                                                        class="delete-modal-btn">
                                                                                        <svg width="16" height="16"
                                                                                            viewBox="0 0 16 16"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763"
                                                                                                stroke="#F73C71"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path
                                                                                                d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398"
                                                                                                stroke="#F73C71"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path
                                                                                                d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375"
                                                                                                stroke="#F73C71"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path d="M6.88666 11H9.10666"
                                                                                                stroke="#F73C71"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path
                                                                                                d="M6.33331 8.33398H9.66665"
                                                                                                stroke="#F73C71"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
     --}}





                                                        </div>
                                                    </div>
                                                </div>

                                        </div>
                                        @endforeach

                                        {{-- <div class="category-main-dishesh active">
                                            @foreach ($potluckDetail['podluck_category_list'] as $category)
                                                <div class="category-list">
                                                    <div class="list-header">
                                                        <span class="me-1 list-sub-head">7</span>
                                                        <div>
                                                            <h5>{{ $category['category'] }}</h5>
                                                        </div>
                                                        <div class="ms-auto d-flex align-items-center ">
                                                            <span
                                                                class="me-2 d-flex align-items-center justify-content-center">
                                                                <svg width="20" height="20"
                                                                    viewBox="0 0 18 18" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                        fill="#23AA26" />
                                                                </svg>
                                                            </span>
                                                            <h6 class="me-3">0 Missing</h6>
                                                            <button href="#" class="me-3">
                                                                <svg width="22" height="22"
                                                                    viewBox="0 0 22 22" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z"
                                                                        fill="#F73C71" />
                                                                </svg>
                                                            </button>
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton1"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <svg width="20" height="20"
                                                                        viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11.6665 4.16667C11.6665 3.25 10.9165 2.5 9.99984 2.5C9.08317 2.5 8.33317 3.25 8.33317 4.16667C8.33317 5.08333 9.08317 5.83333 9.99984 5.83333C10.9165 5.83333 11.6665 5.08333 11.6665 4.16667Z"
                                                                            fill="#0F172A"></path>
                                                                        <path
                                                                            d="M11.6665 15.8327C11.6665 14.916 10.9165 14.166 9.99984 14.166C9.08317 14.166 8.33317 14.916 8.33317 15.8327C8.33317 16.7493 9.08317 17.4993 9.99984 17.4993C10.9165 17.4993 11.6665 16.7493 11.6665 15.8327Z"
                                                                            fill="#0F172A"></path>
                                                                        <path
                                                                            d="M11.6665 10.0007C11.6665 9.08398 10.9165 8.33398 9.99984 8.33398C9.08317 8.33398 8.33317 9.08398 8.33317 10.0007C8.33317 10.9173 9.08317 11.6673 9.99984 11.6673C10.9165 11.6673 11.6665 10.9173 11.6665 10.0007Z"
                                                                            fill="#0F172A"></path>
                                                                    </svg>
                                                                </a>
                                                                <ul class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton1">
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="me-2">
                                                                                    <svg width="20" height="20"
                                                                                        viewBox="0 0 20 20"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M12.5013 18.9577L7.5013 18.9577C2.9763 18.9577 1.04297 17.0243 1.04297 12.4993L1.04297 7.49935C1.04297 2.97435 2.9763 1.04102 7.5013 1.04102L9.16797 1.04102C9.50964 1.04102 9.79297 1.32435 9.79297 1.66602C9.79297 2.00768 9.50964 2.29102 9.16797 2.29102L7.5013 2.29102C3.65964 2.29102 2.29297 3.65768 2.29297 7.49935L2.29297 12.4993C2.29297 16.341 3.65964 17.7077 7.5013 17.7077L12.5013 17.7077C16.343 17.7077 17.7096 16.341 17.7096 12.4993L17.7096 10.8327C17.7096 10.491 17.993 10.2077 18.3346 10.2077C18.6763 10.2077 18.9596 10.491 18.9596 10.8327L18.9596 12.4993C18.9596 17.0243 17.0263 18.9577 12.5013 18.9577Z"
                                                                                            fill="#94A3B8" />
                                                                                        <path
                                                                                            d="M7.08214 14.7424C6.5738 14.7424 6.10714 14.5591 5.76547 14.2258C5.35714 13.8174 5.18214 13.2258 5.2738 12.6008L5.63214 10.0924C5.69881 9.60911 6.01547 8.98411 6.35714 8.64245L12.9238 2.07578C14.5821 0.417448 16.2655 0.417448 17.9238 2.07578C18.8321 2.98411 19.2405 3.90911 19.1571 4.83411C19.0821 5.58411 18.6821 6.31745 17.9238 7.06745L11.3571 13.6341C11.0155 13.9758 10.3905 14.2924 9.90714 14.3591L7.3988 14.7174C7.29047 14.7424 7.18214 14.7424 7.08214 14.7424ZM13.8071 2.95911L7.24047 9.52578C7.08214 9.68411 6.8988 10.0508 6.86547 10.2674L6.50714 12.7758C6.4738 13.0174 6.5238 13.2174 6.6488 13.3424C6.7738 13.4674 6.9738 13.5174 7.21547 13.4841L9.7238 13.1258C9.94047 13.0924 10.3155 12.9091 10.4655 12.7508L17.0321 6.18411C17.5738 5.64245 17.8571 5.15911 17.8988 4.70911C17.9488 4.16745 17.6655 3.59245 17.0321 2.95078C15.6988 1.61745 14.7821 1.99245 13.8071 2.95911Z"
                                                                                            fill="#94A3B8" />
                                                                                        <path
                                                                                            d="M16.5403 8.19124C16.482 8.19124 16.4237 8.18291 16.3737 8.16624C14.182 7.54957 12.4403 5.80791 11.8237 3.61624C11.732 3.28291 11.9237 2.94124 12.257 2.84124C12.5903 2.74957 12.932 2.94124 13.0237 3.27457C13.5237 5.04957 14.932 6.45791 16.707 6.95791C17.0403 7.04957 17.232 7.39957 17.1403 7.73291C17.0653 8.01624 16.8153 8.19124 16.5403 8.19124Z"
                                                                                            fill="#94A3B8" />
                                                                                    </svg>
                                                                                </span>
                                                                                <h6>Edit</h6>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="me-2">
                                                                                    <svg width="16" height="16"
                                                                                        viewBox="0 0 16 16"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M5.66602 3.31398L5.81268 2.44065C5.91935 1.80732 5.99935 1.33398 7.12602 1.33398H8.87268C9.99935 1.33398 10.086 1.83398 10.186 2.44732L10.3327 3.31398"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M12.5669 6.09375L12.1336 12.8071C12.0603 13.8537 12.0003 14.6671 10.1403 14.6671H5.86026C4.00026 14.6671 3.94026 13.8537 3.86693 12.8071L3.43359 6.09375"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path d="M6.88672 11H9.10672"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M6.33398 8.33398H9.66732"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                    </svg>
                                                                                </span>
                                                                                <h6>Delete
                                                                                </h6>
                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="list-created">Category created by:
                                                        {{ $category['created_by'] }}</span>
                                                    <!-- <div class="list-body d-flex align-items-center">
                                                  <span class="me-2">
                                                      <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                          <path d="M6.99984 14.166C10.6732 14.166 13.6665 11.1727 13.6665 7.49935C13.6665 3.82602 10.6732 0.832682 6.99984 0.832682C3.3265 0.832682 0.33317 3.82602 0.33317 7.49935C0.33317 11.1727 3.3265 14.166 6.99984 14.166ZM7.49984 10.166C7.49984 10.4393 7.27317 10.666 6.99984 10.666C6.7265 10.666 6.49984 10.4393 6.49984 10.166V6.83268C6.49984 6.55935 6.7265 6.33268 6.99984 6.33268C7.27317 6.33268 7.49984 6.55935 7.49984 6.83268V10.166ZM6.3865 4.57935C6.41984 4.49268 6.4665 4.42602 6.5265 4.35935C6.59317 4.29935 6.6665 4.25268 6.7465 4.21935C6.8265 4.18602 6.91317 4.16602 6.99984 4.16602C7.0865 4.16602 7.17317 4.18602 7.25317 4.21935C7.33317 4.25268 7.4065 4.29935 7.47317 4.35935C7.53317 4.42602 7.57984 4.49268 7.61317 4.57935C7.6465 4.65935 7.6665 4.74602 7.6665 4.83268C7.6665 4.91935 7.6465 5.00602 7.61317 5.08602C7.57984 5.16602 7.53317 5.23935 7.47317 5.30602C7.4065 5.36602 7.33317 5.41268 7.25317 5.44602C7.09317 5.51268 6.9065 5.51268 6.7465 5.44602C6.6665 5.41268 6.59317 5.36602 6.5265 5.30602C6.4665 5.23935 6.41984 5.16602 6.3865 5.08602C6.35317 5.00602 6.33317 4.91935 6.33317 4.83268C6.33317 4.74602 6.35317 4.65935 6.3865 4.57935Z" fill="#FD5983"/>
                                                      </svg>
                                                  </span>
                                                  <p>Nobody has added anything yet</h5>
                                              </div> -->
                                                    <div class="list-slide">
                                                        <div class="accordion accordion-flush" id="accordioncatList">
                                                            @foreach ($category['items'] as $item)
                                                                <div class="accordion-item active">


                                                                    <h2 class="accordion-header" id="sprite">
                                                                        <button class="accordion-btn accordion-button">
                                                                            <div class="d-flex align-items-center">
                                                                                <span
                                                                                    class="me-2 d-flex align-items-center justify-content-center">
                                                                                    <svg width="18" height="18"
                                                                                        viewBox="0 0 18 18"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                                            fill="#23AA26" />
                                                                                    </svg>
                                                                                </span>
                                                                                <div>
                                                                                    <h5> {{ $item['description'] }}
                                                                                    </h5>
                                                                                    <span
                                                                                        class="list-created">Requested
                                                                                        by:
                                                                                        {{ $item['requested_by'] }}</span>
                                                                                    <span class="host">Host</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="ms-auto d-flex">
                                                                                <h6 class="devide-count">
                                                                                    {{ $item['spoken_quantity'] }}/{{ $item['quantity'] }}
                                                                                </h6>
                                                                                <span
                                                                                    class="accordion-button collapsed"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#sprite-collapseOne"
                                                                                    aria-expanded="false"
                                                                                    aria-controls="sprite-collapseOne"><i
                                                                                        class="fa-solid fa-plus"></i></span>
                                                                            </div>
                                                                        </button>
                                                                    </h2>

                                                                    <div id="sprite-collapseOne"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="sprite"
                                                                        data-bs-parent="#accordionFlushExample">
                                                                        <div class="accordion-body">
                                                                            @foreach ($item['item_carry_users'] as $users)
                                                                                <div
                                                                                    class="accordion-body-content limits-count">
                                                                                    <img src="{{ asset('assets/front/img/header-profi') }}le-img.png"
                                                                                        alt="profile">
                                                                                    <h5>{{ $users['first_name'] }}
                                                                                        {{ $users['last_name'] }}</h5>
                                                                                    <div
                                                                                        class="qty-container qty-custom ms-auto">
                                                                                        <button class="qty-btn-minus"
                                                                                            type="button"><i
                                                                                                class="fa fa-minus"></i></button>
                                                                                        <input type="number"
                                                                                            name="qty"
                                                                                            value="{{ $users['quantity'] }}"
                                                                                            class="input-qty" />
                                                                                        <button class="qty-btn-plus"
                                                                                            type="button"><i
                                                                                                class="fa fa-plus"></i></button>
                                                                                    </div>
                                                                                    <div class="d-flex">
                                                                                        <button type="button"
                                                                                            class="me-3 d-flex align-items-center justify-content-center edit-modal-btn">
                                                                                            <svg width="16"
                                                                                                height="16"
                                                                                                viewBox="0 0 16 16"
                                                                                                fill="none"
                                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                                <path
                                                                                                    d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                                                                                    stroke="#94A3B8"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-miterlimit="10"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                                <path
                                                                                                    d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255"
                                                                                                    stroke="#94A3B8"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-miterlimit="10"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                            </svg>
                                                                                        </button>
                                                                                        <button type="button"
                                                                                            class="delete-modal-btn">
                                                                                            <svg width="16"
                                                                                                height="16"
                                                                                                viewBox="0 0 16 16"
                                                                                                fill="none"
                                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                                <path
                                                                                                    d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763"
                                                                                                    stroke="#F73C71"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                                <path
                                                                                                    d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398"
                                                                                                    stroke="#F73C71"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                                <path
                                                                                                    d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375"
                                                                                                    stroke="#F73C71"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                                <path
                                                                                                    d="M6.88666 11H9.10666"
                                                                                                    stroke="#F73C71"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                                <path
                                                                                                    d="M6.33331 8.33398H9.66665"
                                                                                                    stroke="#F73C71"
                                                                                                    stroke-width="1.5"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round" />
                                                                                            </svg>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach




                                                            <div class="accordion-item active">
                                                                <h2 class="accordion-header" id="root">
                                                                    <button class="accordion-btn accordion-button">
                                                                        <div class="d-flex align-items-center">
                                                                            <span
                                                                                class="me-2 d-flex align-items-center justify-content-center">
                                                                                <svg width="20" height="20"
                                                                                    viewBox="0 0 18 18" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M8.99935 0.666016C4.40768 0.666016 0.666016 4.40768 0.666016 8.99935C0.666016 13.591 4.40768 17.3327 8.99935 17.3327C13.591 17.3327 17.3327 13.591 17.3327 8.99935C17.3327 4.40768 13.591 0.666016 8.99935 0.666016ZM12.9827 7.08268L8.25768 11.8077C8.14102 11.9243 7.98268 11.991 7.81602 11.991C7.64935 11.991 7.49102 11.9243 7.37435 11.8077L5.01602 9.44935C4.77435 9.20768 4.77435 8.80768 5.01602 8.56602C5.25768 8.32435 5.65768 8.32435 5.89935 8.56602L7.81602 10.4827L12.0993 6.19935C12.341 5.95768 12.741 5.95768 12.9827 6.19935C13.2244 6.44102 13.2244 6.83268 12.9827 7.08268Z"
                                                                                        fill="#23AA26" />
                                                                                </svg>
                                                                            </span>
                                                                            <div>
                                                                                <h5>Root Beer</h5>
                                                                                <span class="list-created">Requested
                                                                                    by: Oscar Hernandez</span>
                                                                                <span class="host">Host</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="ms-auto d-flex">
                                                                            <h6 class="devide-count">3/3</h6>
                                                                            <span class="accordion-button collapsed"
                                                                                type="button"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#root-collapseTwo"
                                                                                aria-expanded="false"
                                                                                aria-controls="root-collapseTwo"><i
                                                                                    class="fa-solid fa-plus"></i></span>
                                                                        </div>
                                                                    </button>
                                                                </h2>
                                                                <div id="root-collapseTwo"
                                                                    class="accordion-collapse collapse show"
                                                                    aria-labelledby="root"
                                                                    data-bs-parent="#accordionFlushExample">
                                                                    <div class="accordion-body">
                                                                        <div
                                                                            class="accordion-body-content limits-count">
                                                                            <img src="{{ asset('assets/front/img/header-profi') }}le-img.png"
                                                                                alt="profile">
                                                                            <h5>Pristia Candra</h5>
                                                                            <div
                                                                                class="qty-container qty-custom ms-auto">
                                                                                <button class="qty-btn-minus"
                                                                                    type="button"><i
                                                                                        class="fa fa-minus"></i></button>
                                                                                <input type="number" name="qty"
                                                                                    value="0"
                                                                                    class="input-qty" />
                                                                                <button class="qty-btn-plus"
                                                                                    type="button"><i
                                                                                        class="fa fa-plus"></i></button>
                                                                            </div>
                                                                            <div class="d-flex">
                                                                                <button type="button"
                                                                                    class="me-3 d-flex align-items-center justify-content-center edit-modal-btn">
                                                                                    <svg width="16" height="16"
                                                                                        viewBox="0 0 16 16"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-miterlimit="10"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255"
                                                                                            stroke="#94A3B8"
                                                                                            stroke-width="1.5"
                                                                                            stroke-miterlimit="10"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="delete-modal-btn">
                                                                                    <svg width="16" height="16"
                                                                                        viewBox="0 0 16 16"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763"
                                                                                            stroke="#F73C71"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398"
                                                                                            stroke="#F73C71"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375"
                                                                                            stroke="#F73C71"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path d="M6.88666 11H9.10666"
                                                                                            stroke="#F73C71"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                        <path
                                                                                            d="M6.33331 8.33398H9.66665"
                                                                                            stroke="#F73C71"
                                                                                            stroke-width="1.5"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round" />
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>






                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="listing-arrow">
                                                    <svg width="14" height="8" viewBox="0 0 14 8"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.9401 1.71289L8.05006 6.60289C7.47256 7.18039 6.52756 7.18039 5.95006 6.60289L1.06006 1.71289"
                                                            stroke="#CBD5E1" stroke-width="1.5"
                                                            stroke-miterlimit="10" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            @endforeach
                                        </div> --}}

                                    </div>
                                </div>
                            </div>

                            <!-- ===tab-5-en=== -->

                        </div>
                        <!-- ===tab-content-end=== -->

                    </div>
                    <!-- ===event-center-tabs-main-end=== -->
                </div>
            </div>
            <div class="col-xl-3 col-lg-0">
                <x-event_wall.wall_right_menu :eventInfo="$eventInfo"  />
            </div>
        </div>
    </div>










</main>
<!-- Add Category Modal -->

<div class="modal fade cmn-modal" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editmodalLabel">Add Category</h4>
                <button type="button" id="addCategoryModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('event_potluck.addCategory') }}" method="POST" class="category-form"
                id="categoryForm">
                <div class="modal-body">

                    @csrf
                    <div class="input-form">
                        <input class="form-control" type="text"id="category" name="category"
                            placeholder="ie, Appetizers, Salads, Main Dishes">
                        <span class="sub-con"><span id="charCount">0</span>/30</span>
                        <span class="error_message_category" style="color: red; font-size: 12px;"></span>
                    </div>
                    <input type="hidden" id="event_id" name="event_id" value="{{ $event }}">
                    <div class="qantity-total">
                        <h6>Total Quantity Desired </h6>
                        <div class="qty-container ms-auto">
                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                            <input type="number" id="quantity" name="category_quantity" value="0" class="input-qty" />
                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                        <span class="error_message_quantity" style="color: red; font-size: 12px;"></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ========== main-deshes modal ========== -->
<div class="modal fade cmn-modal" id="maindishes" tabindex="-1" aria-labelledby="maindishesLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="maindishesLabel">Main Dishes</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="category-form" id="categoryItemForm">
                <div class="modal-body">

                    <input type="hidden" id="event_id" name="event_id" value="{{ $event }}">
                    <!-- Example event_id -->
                    <input type="hidden" id="hiddenCategoryId" name="event_potluck_category_id" value="">
                    <div class="input-form">
                        <input class="form-control" type="text" id="text1" name="description"
                            placeholder="ie, Appetizers, Salads, Main Dishes">
                        <span class="sub-con">8/30</span>
                    </div>
            </form>
            <div class="bring-item">
                <h6>I'll bring this item</h6>
                <div class="toggle-button-cover toggle-custom">
                    <div class="buttons-cover">
                        <div class="button r" id="button-1">
                            <input type="checkbox" class="checkbox" name="self_bring_item" value="1"checked />
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="qantity-total">
                <h6>Total Quantity Desired (You & Guests)</h6>
                <div class="qty-container ms-auto">
                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                    <input type="number" name="sub_quantity" value="0" class="input-qty" />
                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal"
                id="saveCategoryBtn">Save</button>
        </div>
        </form>
    </div>
</div>
</div>
<!-- edit catory model Modal -->

<div class="modal fade cmn-modal" id="editcategorymodal" tabindex="-1" aria-labelledby="editmodalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editmodalLabel">Edit Category</h4>
                <button type="button" id="addCategoryModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="" method="POST" class="category-form" id="categoryForms">
                <div class="modal-body">

                    @csrf
                    {{-- @method('PUT') --}}
                    <div class="input-form">
                        <input class="form-control" type="text" id="categorys" name="category"
                            placeholder="ie, Appetizers, Salads, Main Dishes" val="">
                        <span class="sub-con"><span id="charCount">0</span>/30</span>
                        <span class="error_message_category" style="color: red; font-size: 12px;"></span>
                    </div>
                    <input type="hidden" id="event_id" name="event_id" value="{{ $event }}">
                    <div class="qantity-total">
                        <h6>Total Quantity Desired </h6>
                        <div class="qty-container ms-auto">
                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                            <input type="number" id="quantitys" name="quantity" value="" class="input-qty" />
                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                        <span class="error_message_quantity" style="color: red; font-size: 12px;"></span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal -->
<div class="modal fade cmn-modal" id="deletemodal" tabindex="-1" aria-labelledby="deletemodalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="delete-modal">
                    <div class="delete-icon">
                        <img src="./assets/img/deleteicon.svg" alt="delete">
                    </div>
                    <h4>Delete Potluck Category</h4>
                    <h4>Deleting this category will delete all items under this category.</h4>
                    <h5>Category deletion is not reversible.</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="confirmDeleteCategory"
                    data-bs-dismiss="modal">Confirm</button>
            </div>
        </div>
    </div>
</div>
