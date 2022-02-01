


   <div class="modal fade" id="form_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form id="entryForm" name="entryForm">
        <input type="hidden" name="id" id="id">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modelHeading">Add Employee</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_name" class=" control-label">User Name: <small style="color:red">*</small></label>
                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="User Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="email" class=" control-label">Email: <small style="color:red">*</small></label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="col-md-6 password_el">
                            <div class="form-group">
                                <label for="password" class="text-md-right">User Password: <small style="color:red">*</small></label>
                                <input id="password" type="password" class="form-control" name="password" required placeholder="Minimum 8 character">
                            </div>
                        </div>
                        <div class="col-md-6 password_el">
                            <div class="form-group">
                                <label for="confirm-password" class="text-md-right">Confirm Password: <small style="color:red">*</small></label>                                   
                                <input id="confirm-password" type="password" class="form-control" name="password_confirmation" required placeholder="Same to Password">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="full_name" class=" control-label">Full Name<small style="color:red">*</small></label>
                                <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="father_name" class=" control-label">Father's Name</label>
                                <input type="text" class="form-control" name="father_name" id="father_name" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="mother_name" class=" control-label">Mother's Name</label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="phone" class="control-label">Mobile No<small style="color:red">*</small></label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Mobile No" value="" required="">
                            </div>                            
                        </div>
                        @if(Auth::user()->hasRole('Admin'))
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="designation" class=" control-label">Designation<small style="color:red">*</small></label>
                                <select class="form-control" name="designation_id" id="designation" required>
                                    <option value="">Select Designation</option>

                                    @foreach($designations as $designation)
                                        <option value="{{$designation->id}}">{{$designation->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="department" class=" control-label">Department<small style="color:red">*</small></label>
                                <select class="form-control" name="department_id" id="department" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="joining_date" class="control-label">Joining Date<small style="color:red">*</small></label>
                                <input type="text" class="form-control datepicker" name="joining_date" id="joining_date" placeholder="Joining Date" value="{{date('d-m-Y')}}" readonly="true" required>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="extension_date" class="control-label">Extension Date<small style="color:red">*</small></label>
                                <input type="text" class="form-control datepicker" name="extension_date" id="extension_date" placeholder="Extension Date" value="{{date('d-m-Y')}}" readonly="true" required>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="department" class=" control-label">Salary Grade <small style="color:red">*</small></label>
                                <select class="form-control" name="salary_grade" id="salary_grade" required>
                                    <option value="">Select Salary Grade</option>
                                    @if(isset($salary_grade))
                                    @foreach($salary_grade as $key => $val )
                                    <option value="{{$val->id}}" >{{$val->grade_name}}</option>
                                    @endforeach
                                    @endif
                                   
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="year_experience" class="control-label">Years of Experience<small style="color:red">*</small></label>
                                <input type="text" class="form-control" name="year_experience" id="year_experience" placeholder="Years of Experience" value="" required="">
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="prevoius_organization" class="control-label">Prevoius Organization</label>
                                <input type="text" class="form-control" name="prevoius_organization" id="prevoius_organization" placeholder="Prevoius Organization" value="">
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="previous_designation" class="control-label">Previous Designation</label>
                                <input type="text" class="form-control" name="previous_designation" id="previous_designation" placeholder="Previous Designation" value="">
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="academic_institute" class="control-label">Last Academic Qualifiacation &amp; Institute Name<small style="color:red">*</small></label>
                                <textarea class="form-control" name="academic_institute" id="academic_institute" cols="" rows="2" required=""></textarea>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="reference" class="control-label">Reference</label>
                                <textarea class="form-control" name="reference" id="reference" cols="" rows="2"></textarea>
                            </div>                            
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="extension_date" class="control-label">Presnet Address<small style="color:red">*</small></label>
                                <textarea class="form-control" name="present_address" id="present_address" cols="" rows="2"></textarea>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="extension_date" class="control-label">Permanent Address<small style="color:red">*</small></label>
                                <textarea class="form-control" name="permanent_address" id="permanent_address" cols="" rows="2"></textarea>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-success pull-right" id="saveBtn">Save</button>
                </div>
            </div>
        </div>
        </form>
    </div>