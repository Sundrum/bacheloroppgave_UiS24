<div class="modal fade" id="changeGroup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change a group name or description</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-secondary">Click "Update Groups" to save changes</div>
                <form method="POST" action="{{ route('updategroup') }}">
                    @csrf
                    <input type="hidden" id="customerNumber" name="customernumber" value="{{ Session::get('customernumber') }}">
                    @foreach ($sensorunits['groups'] as $group)
                        @if($group['viewgroup_id'] !== '0')
                            <div class="card-header mb-2">
                            {{-- {{ dd($group)}} --}}
                                <input type="hidden" name="result[{{$group['viewgroup_id']}}][viewgroup_id]" value="{{$group['viewgroup_id']}}">
                                <div class="form-group">
                                    <label for="groupName{{$group['viewgroup_id']}}">Group Name</label>
                                    <input type="text" name="result[{{$group['viewgroup_id']}}][name]" class="form-control" id="groupName{{$group['viewgroup_name']}}" value="{{$group['viewgroup_name'] ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="groupDescription{{$group['viewgroup_id']}}">Group Description</label>
                                    <input type="text" name="result[{{$group['viewgroup_id']}}][description]" class="form-control" id="groupDescription{{$group['viewgroup_id']}}" value="{{$group['viewgroup_description'] ?? ''}}">
                                </div>
                            </div>
                        @endif
                    @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary float-right">Update Groups</button>
                </form>
            </div>
        </div>
    </div>
</div>