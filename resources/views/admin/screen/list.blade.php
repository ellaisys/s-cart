@extends('admin.layout')

@section('main')
   <div class="row">
      <div class="col-md-12">
         <div class="box">
      <div class="box-header with-border">
        <div class="pull-right">
          @if (!empty($topMenuRight) && count($topMenuRight))
          <div class="btn-group pull-right" style="margin-right: 10px">
            @foreach ($topMenuRight as $item)
                <div class="menu-right">{!! $item !!}</div>
            @endforeach
          </div>
          @endif
        </div>
        <div class="pull-left">
          @if (!empty($topMenuLeft) && count($topMenuLeft))
            @foreach ($topMenuLeft as $item)
                <div class="menu-left">{!! $item !!}</div>
            @endforeach
          @endif
         </div>
        <!-- /.box-tools -->
      </div>

      <div class="box-header with-border">
         <div class="pull-right">
           @if (!empty($menuRight) && count($menuRight))
           <div class="btn-group pull-right" style="margin-right: 10px">
             @foreach ($menuRight as $item)
                 <div class="menu-right">{!! $item !!}</div>
             @endforeach
           </div>
           @endif
         </div>


         <div class="pull-left">
          @if (!empty($menuLeft) && count($menuLeft))
            @foreach ($menuLeft as $item)
                <div class="menu-left">{!! $item !!}</div>
            @endforeach
          @endif
          @if (!empty($menuSort))
          <div class="menu-left">{!! $menuSort !!}</div>
          @endif
        </div>

      </div>
      <!-- /.box-header -->
    <section id="pjax-container" class="table-list">
      <div class="box-body table-responsive no-padding" >
         <table class="table table-hover">
            <thead>
               <tr>
                @foreach ($listTh as $key => $th)
                    <th>{!! $th !!}</th>
                @endforeach
               </tr>
            </thead>
            <tbody>
                @foreach ($dataTr as $keyRow => $tr)
                    <tr>
                        @foreach ($tr as $key => $trtd)
                            <td>{!! $trtd !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
         </table>
      </div>
      <div class="box-footer clearfix">
         {!! $resultItems??'' !!}
         {!! $pagination??'' !!}
      </div>
    </section>
      <!-- /.box-body -->
    </div>
  </div>
</div>
@endsection


@push('styles')
<style type="text/css">
  .box-body td,.box-body th{
  max-width:150px;word-break:break-all;
}
</style>
@endpush

@push('scripts')
    {{-- //Pjax --}}
   <script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>

  <script type="text/javascript">

    $('.grid-refresh').click(function(){
      $.pjax.reload({container:'#pjax-container'});
    });

      $(document).on('submit', '#button_search', function(event) {
        $.pjax.submit(event, '#pjax-container')
      })

    $(document).on('pjax:send', function() {
      $('#loading').show()
    })
    $(document).on('pjax:complete', function() {
      $('#loading').hide()
    })

    // tag a
    $(function(){
     $(document).pjax('a.page-link', '#pjax-container')
    })


    $(document).ready(function(){
    // does current browser support PJAX
      if ($.support.pjax) {
        $.pjax.defaults.timeout = 2000; // time in milliseconds
      }
    });

    {!! $scriptSort??'' !!}

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
    confirmButtonText: '{{ trans('admin.confirm_delete') }}',
    confirmButtonColor: "#DD6B55",
    cancelButtonText: 'No, cancel!',
    reverseButtons: true,

    preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                method: 'post',
                url: '{{ $url_delete_item }}',
                data: {
                  ids:ids,
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    if(data.error == 1){
                      swalWithBootstrapButtons.fire(
                        'Cancelled',
                        data.msg,
                        'error'
                      )
                      $.pjax.reload('#pjax-container');
                      return;
                    }else{
                      $.pjax.reload('#pjax-container');
                      resolve(data);
                    }

                }
            });
        });
    }

  }).then((result) => {
    if (result.value) {
      swalWithBootstrapButtons.fire(
        'Deleted!',
        'Item has been deleted.',
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
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
@endpush
