@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">Tokens</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                    </li>

                    <li class="breadcrumb-item active">
                     Token
                    </li>

                </ol>

            </div>

            <div>

            </div>

        </div>

        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">



                        <div class="card-body">

                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">
                                {{trans('lang.processing')}}
                            </div>

                            <div class="userlist-topsearch d-flex mb-3">

                                <div class="userlist-top-left">
                                    <a class="nav-link" href="{!! route('tokens.create') !!}"><i
                                            class="fa fa-plus mr-2"></i>Token Create</a>
                                </div>


                            </div>

                            <div class="table-responsive m-t-10">

                                <table id="example24"
                                       class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>

                                        <th>Title</th>
                                        <th>Tokens</th>
                                        <th>Expiry Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_list12">
                                    @if(!empty($tokens) )
                                        @foreach($tokens as $token)
                                        <tr>

                                            <td>{{  $token->title }}</td>
                                            <td>{{  $token->up_to }}</td>
                                            <td>{{ $token->expiry_date }}</td>
                                            <td>{{ $token->amount }}</td>
                                            <td>

                                                 <a href="{{route('tokens.edit', $token->id)}}"><i
                                                             class="fa fa-edit mx-auto"></i></a>
                                                             <a id="'+val.id+'" class=" mx-auto text-primary do_not_delete text-danger"
                                                                                           name="coupon-delete"
                                                                                           href="{{route('delete_tokens', $token->id)}}"><i
                                                             class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>

                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" align="center">{{trans("lang.no_result")}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
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

@endsection


