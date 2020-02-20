@extends('admin.layout')

@section('main')

<div class="row">

  <div class="col-md-6">

    <div class="box box-primary">

      <div class="box-body table-responsive no-padding box-primary">
       <table class="table table-hover">
         <thead>
           <tr>
             <th>{{ trans('cache.config_manager.field') }}</th>
             <th>{{ trans('cache.config_manager.value') }}</th>
           </tr>
         </thead>
         <tbody>
          <tr>
            <td colspan="2">
              <button type="button" class="btn btn-block btn-success btn-sm">
                <i class="fa fa-refresh"></i> {{ trans('cache.config_manager.cache_refresh') }}
              </button>
            </td>
          </tr>
          <tr>
            <td>{{ trans('cache.config_manager.cache_status') }}</td>
            <td>
              <a href="#" class="fied-required editable editable-click" data-name="cache_status" data-type="select" data-pk="" data-source="{{ json_encode(['1'=>'ON','0'=>'OFF']) }}" data-url="{{ route('admin_store_value.update') }}" data-title="{{ trans('cache.config_manager.cache_status') }}" data-value="{{ sc_config('cache_status') }}" data-original-title="" title=""></a></td>
          </tr>
          <tr>
            <td>{{ trans('cache.config_manager.cache_time') }}</td>
            <td>
              <a href="#" class="cache-time data-cache_time"  data-name="cache_time" data-type="text" data-pk="" data-url="{{ route('admin_store_value.update') }}" data-title="{{ trans('cache.config_manager.cache_time') }}">{{ sc_config('cache_time') }}</a>
          </tr>
           @foreach ($configs as $config)
           @if (!in_array($config->key, ['cache_status', 'cache_time']))
           <tr>
            <td>{{ sc_language_render($config->detail) }}</td>
            <td><input type="checkbox" name="{{ $config->key }}"  {{ $config->value?"checked":"" }}></td>
          </tr>
           @endif
           @endforeach
         </tbody>
       </table>
      </div>
    </div>
  </div>


</div>


@endsection


@push('styles')
<!-- Ediable -->
<link rel="stylesheet" href="{{ asset('admin/plugin/bootstrap-editable.css')}}">
@endpush

@push('scripts')
<!-- Ediable -->
<script src="{{ asset('admin/plugin/bootstrap-editable.min.js')}}"></script>

<script type="text/javascript">
  // Editable
$(document).ready(function() {

       {{-- $.fn.editable.defaults.mode = 'inline'; --}}
      $.fn.editable.defaults.params = function (params) {
        params._token = "{{ csrf_token() }}";
        return params;
      };
        $('.fied-required').editable({
        validate: function(value) {
            if (value == '') {
                return '{{  trans('admin.not_empty') }}';
            }
        },
        success: function(data) {
          if(data.error == 0){
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });

            Toast.fire({
              type: 'success',
              title: '{{ trans('admin.msg_change_success') }}'
            })
          }
      }
    });

    $('.cache-time').editable({
      ajaxOptions: {
      type: 'post',
      dataType: 'json'
      },
      validate: function(value) {
        if (value == '') {
            return '{{  trans('admin.not_empty') }}';
        }
        if (!$.isNumeric(value)) {
            return '{{  trans('admin.only_numeric') }}';
        }
        if (parseInt(value) < 0) {
          return '{{  trans('admin.gt_numeric_0') }}';
        }
     },
  
      success: function(response, newValue) {
            // console.log(response);
    }
  });
  

    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      }).on('ifChanged', function(e) {
      var isChecked = e.currentTarget.checked;
      isChecked = (isChecked == false)?0:1;
      var name = $(this).attr('name');
        $.ajax({
          url: '{{ route('admin_store_value.update') }}',
          type: 'POST',
          dataType: 'JSON',
          data: {"name": name,"value":isChecked,"_token": "{{ csrf_token() }}",},
        })
        .done(function(data) {
          if(data.error == 0){
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });
  
            Toast.fire({
              type: 'success',
              title: '{{ trans('admin.msg_change_success') }}'
            })
          }
        });
  
        });
  
    });
  



});
</script>

    {{-- //Pjax --}}
   <script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>

  <script type="text/javascript">

    $('.grid-refresh').click(function(){
      $.pjax.reload({container:'#pjax-container'});
    });

    $(document).on('pjax:send', function() {
      $('#loading').show()
    })
    $(document).on('pjax:complete', function() {
      $('#loading').hide()
    })
    $(document).ready(function(){
    // does current browser support PJAX
      if ($.support.pjax) {
        $.pjax.defaults.timeout = 2000; // time in milliseconds
      }
    });

    {!! $script_sort??'' !!}

    $(document).on('ready pjax:end', function(event) {
      $('.table-list input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
    })

  </script>
    {{-- //End pjax --}}


<script type="text/javascript">
{{-- sweetalert2 --}}
var selectedRows = function () {
    var selected = [];
    $('.grid-row-checkbox:checked').each(function(){
        selected.push($(this).data('id'));
    });

    return selected;
}

$('.grid-trash').on('click', function() {
  var ids = selectedRows().join();
  deleteItem(ids);
});

  function deleteItem(ids){
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true,
  })

  swalWithBootstrapButtons.fire({
    title: '{{ trans('admin.confirm_delete') }}',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: '{{ trans('admin.confirm_delete_yes') }}',
    confirmButtonColor: "#DD6B55",
    cancelButtonText: '{{ trans('admin.confirm_delete_no') }}',
    reverseButtons: true,

    preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                method: 'post',
                url: '{{ $urlDeleteItem ?? '' }}',
                data: {
                  ids:ids,
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    $.pjax.reload('#pjax-container');
                    resolve(data);
                }
            });
        });
    }

  }).then((result) => {
    if (result.value) {
      swalWithBootstrapButtons.fire(
        '{{ trans('admin.confirm_delete_deleted') }}',
        '{{ trans('admin.confirm_delete_deleted_msg') }}',
        'success'
      )
    } else if (
      // Read more about handling dismissals
      result.dismiss === Swal.DismissReason.cancel
    ) {
      // swalWithBootstrapButtons.fire(
      //   'Cancelled',
      //   'Your imaginary file is safe :)',
      //   'error'
      // )
    }
  })
}
{{--/ sweetalert2 --}}

</script>

@endpush
