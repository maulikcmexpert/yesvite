<!-- jQuery -->
<!-- <script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://kit.fontawesome.com/5da2e3556b.js" crossorigin="anonymous"></script>
<script src="{{asset('assets/admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->

<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/admin/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<!-- <script src="{{asset('assets/admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script> -->
<!-- <script src="{{asset('assets/admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script> -->
<!-- jQuery Knob Chart -->
<!-- <script src="{{asset('assets/admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script> -->
<!-- daterangepicker -->
<script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/assets/js/adminlte.js')}}"></script>

<script src="{{asset('assets/admin/js/jquery-validate.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery-validate-additional.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/assets/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{asset('assets/admin/assets/js/pages/dashboard.js')}}"></script> -->
<!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{asset('assets/admin/assets/js/jquery.dataTables.min.js')}}"></script>

<script src="{{asset('assets/admin/assets/js/moment.js')}}"></script>
<script src="{{asset('assets/admin/assets/js/common.js')}}"></script>
<script src="{{asset('assets/admin/assets/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mockjax/2.6.1/jquery.mockjax.min.js" integrity="sha512-LbhUoRYSZ3tFp6RrcQOwGL2P/SlbfF9B+2yiJAMcJhuxJQTgvzWaG6W+XxX9t8+aQ8z+zUxx/XQ0fOo1/ft4tA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fontfaceobserver/2.1.0/fontfaceobserver.standalone.js"></script>
@if(config('app.url') == 'https://yesvite.cmexpertiseinfotech.in')
<script src="{{ asset('assets/event/js/fontLoder.js') }}"></script>
@else
<script src="{{ asset('assets/event/js/fontLoder_live.js') }}"></script>
@endif
<script>
  $(document).ready(function() {
    // Hide success message after 3 seconds
    $(".alert-success").delay(1000).fadeOut("slow");
    $(".alert-danger").delay(1000).fadeOut("slow");
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
{{-- <script src="{{ asset('assets/front/js/design.js') }}"></script> --}}
<script>
  $(document).ready(function () {
    toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                };
            @if (session('msg'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                };
                toastr.success("{{ session('msg') }}");
            @endif
            @if (session('msg_error'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                };
                toastr.error("{{ session('msg_error') }}");
            @endif
            @if (session('error'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                };
                toastr.error("{{ session('error') }}");
            @endif
        });
</script>