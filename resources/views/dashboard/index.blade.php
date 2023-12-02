@extends('layouts.app')

@section('content')
    <div class="report-wrapper">
        <h5 class="page-title"><i class="fas fa-tachometer-alt"></i> Dashboard</h5>
        <h4 class="main-title">Total number of users in the app</h4>

        <span class="filter-title">Sort by:</span>

        <ul class="filter-wrapper">
            <li class="filter-all-user" data-sortBy="all-users">
                <input type="checkbox" name="all-user" value="all-user" id="all-user"/>
                <label for="all-user">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 17" width="21" height="17">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="m-371-171h1366v768h-1366z"/>
                            </clipPath>
                        </defs>
                        <style>
                            .a {
                                fill: #dfdfdf
                            }
                        </style>
                        <g clip-path="url(#cp1)">
                            <path class="a"
                                  d="m1 11.5c0.1-0.2 0.1-0.5 0.3-0.8 0.4-1 1.2-1.6 2.3-1.7 0.6 0 1.2 0 1.7 0q0 0.1 0 0.2c-0.1 0.4-0.2 0.8-0.2 1.3q0 2.7 0 5.5 0 0.3 0.1 0.7-0.3 0-0.7 0-0.8 0-1.7 0c-0.9 0-1.7-0.6-1.8-1.5q0 0 0 0 0-1.8 0-3.7z"/>
                            <path class="a"
                                  d="m10.8 16.7q-1.9 0-3.9 0c-0.4 0-0.6-0.2-0.6-0.7q0-2.8 0-5.6c0-1.4 0.8-2.5 2.2-2.8q0.3-0.1 0.6-0.1 1.7 0 3.5 0c1.3 0 2.4 0.9 2.8 2.3q0 0.3 0 0.6 0 2.8 0 5.6 0 0.7-0.6 0.7-2 0-4 0z"/>
                            <circle class="a" cx="10.8" cy="3.4" r="3.4"/>
                            <path class="a"
                                  d="m16.5 16.7q0.1-0.7 0.1-1.3 0-2.3 0-4.7c0-0.6-0.1-1.1-0.2-1.6q-0.1 0-0.1-0.1 0.1 0 0.2 0c0.5 0 1.1 0 1.6 0 1.4 0.1 2.6 1.3 2.6 2.8q0 1.6 0 3.2c0 0.9-0.7 1.6-1.6 1.7-0.8 0-1.7 0-2.6 0z"/>
                            <circle class="a" cx="4.8" cy="5.7" r="2.6"/>
                            <circle class="a" cx="16.9" cy="5.7" r="2.6"/>
                        </g>
                    </svg>

                    <span class="title">all users</span>
                </label>
            </li>

            <li class="new-user-filter" data-sortBy="new-users">
                <input type="checkbox" name="new-user" value="new-user" id="new-user"/>

                <label for="new-user">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 18" width="16" height="18">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="m-519-170h1366v768h-1366z"/>
                            </clipPath>
                        </defs>
                        <style>
                            .a {
                                fill: #dfdfdf
                            }
                        </style>
                        <g clip-path="url(#cp1)">
                            <circle class="a" cx="7.7" cy="4.7" r="4.7"/>
                            <path class="a"
                                  d="m10.4 15q0 0.3 0 0.7c-0.1 0.8 0.3 1.7 1.5 1.9q-0.1 0-0.2 0-4.9 0-9.8 0c-0.9 0-1.7-0.6-1.8-1.6q0-0.7 0.1-1.4c0.4-2.4 1.7-4.2 3.8-5.4q3 2.3 6.5 0.7c-0.2 0.5-0.1 1-0.1 1.6q-0.5 0-0.9 0c-1 0-1.8 0.8-1.8 1.8 0.1 0.9 0.8 1.6 1.7 1.7q0.5 0 1 0z"/>
                            <path class="a"
                                  d="m12.7 12.6q0.4 0 0.8 0 0.6 0 1.3 0c0.3 0 0.6 0.3 0.6 0.6 0 0.3-0.3 0.6-0.6 0.6q-0.9 0-1.9 0-0.1 0-0.2 0 0 0.2 0 0.3 0 0.9 0 1.7c0 0.4-0.2 0.7-0.6 0.7-0.3-0.1-0.5-0.3-0.6-0.7q0-1 0-2-0.1 0-0.2 0-0.9 0-1.8 0c-0.3 0-0.6-0.2-0.6-0.6 0-0.3 0.3-0.6 0.6-0.6q1 0 2 0v-0.2q0-0.9 0-1.8c0.1-0.3 0.3-0.6 0.6-0.6 0.4 0 0.6 0.3 0.6 0.6q0 1 0 2z"/>
                        </g>
                    </svg>

                    <span class="title">new users</span>
                </label>
            </li>

            <li class="age-range-filter" data-sortBy="age-range">
                <label for="age">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23 23" width="23" height="23">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="m-659-169h1366v768h-1366z"/>
                            </clipPath>
                        </defs>
                        <style>
                            .a {
                                fill: #dfdfdf
                            }
                        </style>
                        <g clip-path="url(#cp1)">
                            <path class="a"
                                  d="m18.8 0q0.2 0.1 0.5 0.1c0.9 0.4 1.4 1.2 1.3 2.2 0 0.8-0.7 1.6-1.6 1.8q0 0-0.1 0.1 0.2 0 0.4 0.1c2 0.3 3.4 2 3.4 4.1q0 2.4 0 4.9 0 1.4-1.3 1.9c-0.1 0.1-0.1 0.2-0.1 0.3q0 3.1 0 6.2c0 0.6-0.2 0.8-0.8 0.8q-2 0-3.9 0c-0.6 0-0.9-0.2-0.9-0.8q0-1.7 0.1-3.4c0-0.2 0-0.3 0.1-0.4 0.7-0.6 1.1-1.4 1.1-2.4q0-1.5 0-3.1c-0.1-1.8-0.9-3.3-2.4-4.3-0.2-0.1-0.2-0.2-0.2-0.4 0.3-1.9 1.8-3.2 3.7-3.5q0 0 0.1 0-0.3-0.1-0.5-0.2c-0.8-0.4-1.4-1.2-1.3-2.1 0.2-1 0.8-1.7 1.8-1.9q0 0 0.1 0 0.2 0 0.5 0z"/>
                            <path class="a"
                                  d="m0.2 16.4q0.1-0.2 0.1-0.5c0.4-1.7 1.8-3 3.6-3.2 0 0 0.1 0 0.2 0q-1-0.2-1.5-1-0.5-0.8-0.2-1.6c0.2-1.1 1.1-1.7 2.2-1.6 1 0.1 1.8 0.9 1.9 2 0.1 1-0.7 1.9-1.8 2.2q0.3 0 0.4 0c2.1 0.4 3.4 2 3.5 4.1 0 0.4 0 0.8-0.1 1.2-0.1 0.7-0.5 1.2-1.1 1.4-0.2 0.1-0.2 0.2-0.2 0.4q0 0.9 0 1.9c0 0.6-0.3 0.8-0.8 0.8q-2 0-4 0-0.8 0-0.8-0.8 0-1 0-2c0-0.1 0-0.2-0.1-0.2q-1-0.5-1.3-1.6 0 0 0 0 0-0.8 0-1.5z"/>
                            <path class="a"
                                  d="m11.7 8.4c2 0.3 3.5 1.5 3.9 3.4q0 0.4 0 0.9 0.1 1.3 0 2.7 0 1.4-1.2 2c-0.2 0-0.2 0.1-0.2 0.2q0 2.1 0 4.1c0 0.6-0.2 0.8-0.8 0.8q-1.9 0-3.9 0-0.8 0-0.8-0.8 0-0.7 0-1.4c0-0.1 0-0.2 0.1-0.2q1-0.9 1.1-2.3c0.1-1.2 0-2.4-0.6-3.5q-0.7-1.3-1.9-2c-0.1-0.1-0.1-0.2-0.1-0.3 0.3-1.9 1.9-3.4 3.9-3.6-0.6-0.1-1.1-0.4-1.5-0.9q-0.5-0.8-0.3-1.7c0.3-1 1.2-1.6 2.2-1.6 1.1 0.1 1.9 0.9 2 2 0 1-0.7 1.9-1.9 2.2z"/>
                        </g>
                    </svg>

                    <span class="title">
                        <div class="selectbox">
                            <select name="age" id="age">
                                <option value="">Select Age</option>
                                <option value="20-50">20-50</option>
                                <option value="15-20">15-20</option>
                                <option value="21-25">21-25</option>
                                <option value="26-30">26-30</option>
                                <option value="31-35">31-35</option>
                                <option value="40-100">40-100</option>
                            </select>
                        </div>
                    </span>
                </label>
            </li>

            <li class="country-filter" data-sortBy="country">
                <label for="country">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 24" width="21" height="24">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="m-803-168h1366v768h-1366z"/>
                            </clipPath>
                        </defs>
                        <style>
                            .a {
                                fill: #dfdfdf
                            }
                        </style>
                        <g clip-path="url(#cp1)">
                            <path fill-rule="evenodd" class="a"
                                  d="m10.9 0c0.6 0.1 1.1 0.2 1.7 0.3 1.5 0.4 2.7 1.3 3.7 2.5q1.1 1.3 1.5 3c0.4 2 0.1 3.9-1 5.6q-2.8 4.4-5.6 8.9-0.1 0-0.1 0.1c-0.3 0.5-0.9 0.5-1.2 0q-0.5-0.8-1-1.6-2.4-3.7-4.7-7.3-1.5-2.4-1.1-5.3c0.6-3 2.8-5.4 5.9-6 0.3-0.1 0.7-0.2 1-0.2zm3 7.5c0-1.9-1.6-3.4-3.4-3.4-1.9 0-3.4 1.5-3.4 3.4 0 1.9 1.5 3.4 3.4 3.4 1.9 0 3.4-1.6 3.4-3.4z"/>
                            <path class="a"
                                  d="m9.4 23.2q-0.5 0-1-0.1c-1.6-0.1-3.2-0.4-4.7-0.9-0.9-0.3-1.7-0.6-2.4-1.2-0.3-0.3-0.6-0.6-0.8-0.9-0.4-0.8-0.2-1.7 0.4-2.4 0.6-0.7 1.4-1.1 2.3-1.5 0.6-0.2 1.2-0.3 1.8-0.5 0.1-0.1 0.2 0 0.3 0.1q1.6 2.6 3.2 5.1c0.5 0.6 1 1 1.8 1.1 0.8 0.1 1.5-0.2 2-0.9 0.6-0.8 1.1-1.7 1.6-2.6q0.9-1.3 1.8-2.7c0.1-0.1 0.2-0.2 0.3-0.1 1 0.3 2 0.6 2.9 1.1 0.6 0.3 1 0.7 1.4 1.2 0.6 0.8 0.5 1.8-0.2 2.6-0.6 0.6-1.3 1-2.1 1.3-1.4 0.6-2.9 1-4.5 1.1q-0.9 0.1-1.8 0.2-0.1 0-0.2 0z"/>
                        </g>
                    </svg>

                    <span class="title">
                            <div class="selec tbox">
                                <select name="country" id="country">
{{--                                <option value="">Select Country</option>--}}
                                @isset($countries)
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    @endisset
                            </select>

                                <input type="checkbox" id="selectAllCountries">
                            <label for="selectAllCountries">Select All</label>
                            </div>
                        </span>
                </label>
            </li>

            <li class="active-user-filter" data-sortBy="active-users">
                <input type="checkbox" name="active-user" value="active-user" id="active-user"/>

                <label for="active-user">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 24" width="18" height="24">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="m-950-169h1366v768h-1366z"/>
                            </clipPath>
                        </defs>
                        <style>
                            .a {
                                fill: #dfdfdf
                            }
                        </style>
                        <g clip-path="url(#cp1)">
                            <path class="a"
                                  d="m4.8 23.2q0 0-0.1 0c-0.7-0.2-1.1-0.9-0.9-1.5q0.8-4.1 1.6-8.2 0.1-0.6 0.2-1.2c0.2-0.8-0.2-1.6-1-2q-1.7-1-3.5-2c-0.5-0.2-0.7-0.6-0.7-1.2 0.1-0.8 0.8-1.4 1.6-1.1q2.6 0.7 5.2 1.5 1.8 0.5 3.5 0 2.6-0.8 5.2-1.5c0.8-0.3 1.5 0.3 1.6 1.1 0 0.6-0.2 1-0.7 1.2q-1.8 1-3.6 2c-0.8 0.5-1.1 1.2-0.9 2.1q0.9 4.6 1.8 9.2c0.2 0.8-0.2 1.4-1 1.6q0 0 0 0h-0.5c-0.5-0.1-0.9-0.5-1.1-1-0.1-0.4-0.3-0.8-0.5-1.1q-0.7-1.7-1.5-3.3c-0.1-0.4-0.5-0.6-0.9-0.3q-0.1 0.1-0.2 0.3-1 2.2-2 4.4c-0.2 0.5-0.6 0.9-1.1 1z"/>
                            <circle class="a" cx="9" cy="3.1" r="3.1"/>
                        </g>
                    </svg>

                    <span class="title">Active Users</span>
                </label>
            </li>
        </ul>

        <div class="chart-wrapper">
            <div class="user-chart">
                <h5 class="title">User</h5>
                <canvas id="userChart" width="400" height="400"></canvas>
            </div>

            <div class="doughnut-chart">
                <div class="selectbox">
                    <select name="" id="">
                        <option value="1day">1 Day</option>
                        <option value="1week">1 Week</option>
                        <option value="1month">1 Month</option>
                        <option value="1year">1 Year</option>
                    </select>

                </div>

                <div class="chart-progress">
                    <div class="barOverflow">
                        <div class="bar"></div>
                    </div>
                    <p class="bar-text"><span>0</span> user <small class="d-block">in a day</small></p>

                    <span class="bar-percentage d-none"><strong>20</strong></span>
                </div>
            </div>
        </div>

        <div class="download-btn-area mt-5">
            <button type="button" class="prediciton-btn" id="btnDashboardStatDownloadAsPdf">Download as PDF</button>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        jQuery(document).ready(function ($) {
            var all_user_data = '';
            var new_user_data = '';
            var age_range_data = '';
            var country_data = '';
            var active_user_data = '';

            $('.filter-all-user input[type="checkbox"]').on('click', function () {
                new_user_data = '';
                active_user_data = '';
                age_range_data = '';
                country_data = '';

                $('.new-user-filter').removeClass('active');
                $('.active-user-filter').removeClass('active');
                $('.age-range-filter').removeClass('active');
                $('.country-filter').removeClass('active');

                $('.new-user-filter input[type="checkbox"]').prop('checked', false);
                $('.age-range-filter select').prop('selected', false);
                $('.country-filter select').prop('selected', false);
                $('.active-user-filter input[type="checkbox"]').prop('checked', false);

                if ($(this).is(":checked")) {
                    all_user_data = $(this).val();
                    $(this).parent('li').addClass('active');

                } else {
                    all_user_data = '';
                    $(this).parent('li').removeClass('active');
                }

                sortByClicked();
            });

            //---------on click new-user-filter----------
            $('.new-user-filter input[type="checkbox"]').on('click', function () {
                all_user_data = '';
                $('.filter-all-user').removeClass('active');
                $('.filter-all-user input[type="checkbox"]').prop('checked', false);

                if ($(this).is(":checked")) {
                    new_user_data = $(this).val();

                    $(this).parent('li').addClass('active');

                } else {
                    new_user_data = '';
                    $(this).parent('li').removeClass('active');
                }

                sortByClicked();
            });

            //---------on click age-range-filter----------
            $('.age-range-filter select').on('change', function () {
                all_user_data = '';
                $('.filter-all-user').removeClass('active');
                $('.filter-all-user input[type="checkbox"]').prop('checked', false);

                if ($(this).val()) {
                    age_range_data = $(this).val();
                    $(this).parents('li').addClass('active');

                } else {
                    age_range_data = '';
                    $(this).parents('li').removeClass('active');
                }

                sortByClicked();
            });

            //---------on click age-range-filter----------
            $('.country-filter select').on('change', function () {
                all_user_data = '';
                $('.filter-all-user').removeClass('active');
                $('.filter-all-user input[type="checkbox"]').prop('checked', false);

                if ($(this).val()) {
                    country_data = $(this).val();
                    $(this).parents('li').addClass('active');

                } else {
                    country_data = '';
                    $(this).parents('li').removeClass('active');
                }

                sortByClicked();
            });

            //---------on click active-user-filter----------
            $('.active-user-filter input[type="checkbox"]').on('click', function () {
                all_user_data = '';
                $('.filter-all-user').removeClass('active');
                $('.filter-all-user input[type="checkbox"]').prop('checked', false);

                if ($(this).is(":checked")) {
                    active_user_data = $(this).val();

                    $(this).parent('li').addClass('active');

                } else {
                    active_user_data = '';
                    $(this).parent('li').removeClass('active');
                }

                sortByClicked();
            });

            $("#country").select2();
            $("#selectAllCountries").click(function(){
                if($("#selectAllCountries").is(':checked') ){
                    $("#country > option").prop("selected","selected");
                    $("#country").trigger("change");

                } else {
                    $("#country > option").prop("selected","");
                    $("#country > option").removeAttr("selected");
                    $("#country").trigger("change");
                }
            });

        });


        const ctx = document.getElementById('userChart');

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['1 day', '1 week', '1 month', ' 1 year'],
                datasets: [{
                    label: 'Users',
                    data: [100, 500, 1000, 5000],
                    backgroundColor: [
                        '#48c4b9',
                        '#48c4b9',
                        '#48c4b9',
                        '#48c4b9',
                    ],
                    borderRadius: 50,
                    width: 3
                }]
            },
            options: {
                barThickness: 10,

                scales: {
                    y: {
                        beginAtZero: true
                    }

                }
            }
        });


        function sortByClicked() {
            const sortBy = [];
            $('.filter-wrapper li').each(function (index, li) {
                if (~$(li).attr('class').indexOf(' active')) {
                    sortBy.push($(li).attr('data-sortBy'));
                }
            });

            $.ajax({
                url: '{{ route('ajax.dashboard.sort-by') }}',
                type: 'post',
                data: {
                    sort_by: sortBy,
                    age_range: $('select[name=age]').val(),
                    country: $('select[name=country]').val()
                },
                success: function (response) {
                    myChart.data.datasets[0].data = response.data.userChart.data.datasets;
                    myChart.update();
                }
            });
        }


        function doughnutProgress(progressVal) {
            $(".doughnut-chart .chart-progress").each(function () {

                var $bar = $(this).find(".bar");
                var $val = $(this).find(".bar-percentage");
                var perc = parseInt(progressVal, 10);

                $({p: 0}).animate({
                    p: perc
                }, {
                    duration: 1000,
                    easing: "swing",
                    step: function (p) {
                        $bar.css({
                            transform: "rotate(" + (45 + (p * 1.8)) + "deg)", // 100%=180° so: ° = % * 1.8
                        });
                        $val.text(p | 0);
                    }
                });
            });
        }


        $('.chart-progress .bar-text span').text(myChart.data.datasets[0].data[0]);

        $('.doughnut-chart select').on('change', function () {
            $('.chart-progress .bar-text small').text('in ' + $(this).find('option:selected').text());

            var oneDayData = myChart.data.datasets[0].data[0];
            var oneWeekData = myChart.data.datasets[0].data[1];
            var oneMonthData = myChart.data.datasets[0].data[2];
            var oneYearData = myChart.data.datasets[0].data[3];

            var oneDayPercent = (oneDayData / oneYearData) * 100;
            var oneWeekPercent = (oneWeekData / oneYearData) * 100;
            var oneMonthPercent = (oneMonthData / oneYearData) * 100;
            var oneYearPercent = (oneYearData / oneYearData) * 100;


            if ('1day' == $(this).val()) {
                doughnutProgress(oneDayPercent);
                $('.chart-progress .bar-text span').text(myChart.data.datasets[0].data[0]);

            } else if ('1week' == $(this).val()) {
                doughnutProgress(oneWeekPercent);
                $('.chart-progress .bar-text span').text(myChart.data.datasets[0].data[1]);

            } else if ('1month' == $(this).val()) {
                doughnutProgress(oneMonthPercent);
                $('.chart-progress .bar-text span').text(myChart.data.datasets[0].data[2]);

            } else {
                doughnutProgress(oneYearPercent);
                $('.chart-progress .bar-text span').text(myChart.data.datasets[0].data[3]);
            }

        });

        $('#btnDashboardStatDownloadAsPdf').on('click', function () {
            var newCanvas = document.querySelector('#userChart');

            //create image from dummy canvas
            var newCanvasImg = newCanvas.toDataURL("user-chart/png", 1.0);

            const sortBy = [];
            $('.filter-wrapper li').each(function (index, li) {
                if (~$(li).attr('class').indexOf(' active')) {
                    sortBy.push($(li).attr('data-sortBy'));
                }
            });

            $('#downloadUserChartAsPdfForm').remove();
            var form = $('<form/>', {
                action: "{{ route('dashboard.download-user-chart-as-pdf') }}",
                method: "POST",
                id: 'downloadUserChartAsPdfForm'
            })
                .append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }))
                .append($('<input>', {
                    type: 'text',
                    name: 'user_chart',
                    value: newCanvasImg
                }))
                .append($('<input>', {
                    type: 'text',
                    name: 'sort_by',
                    value: sortBy
                }))
                .append($('<input>', {
                    type: 'text',
                    name: 'age_range',
                    value: $('select[name=age]').val()
                }))
                .append($('<input>', {
                    type: 'text',
                    name: 'country',
                    value: $('select[name=country]').val()
                }));
            $(document.body).append(form);
            form.submit();
        });

    </script>

    <!--<script>
        const doughnutctx = document.getElementById('doughnutChart');
        const doughnutmyChart = new Chart(doughnutctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    label: 'Users',
                    data: [100, 500, 1000, 5000, 10000],
                    backgroundColor: [
                        '#48c4ce',
                        '#48c4bb',
                        '#48c4bd',
                        '#48c4be',
                    ],
                }]
            },
            options: {}
        });
    </script>-->
@endpush
