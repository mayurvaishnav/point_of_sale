<div class="form-group">
    <label for="job_name">Name</label>
    <input type="text" name="job_name" class="form-control @error('job_name') is-invalid @enderror"
           id="job_name"
           placeholder="Job Name" value="{{ old('job_name', $job->job_name ?? '') }}" required>
    @error('job_name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="email_subject">Email Subject</label>
    <input type="text" name="email_subject" class="form-control @error('email_subject') is-invalid @enderror" id="email_subject"
           placeholder="Email Subject" value="{{ old('email_subject', $job->email_subject ?? '') }}" required>
    @error('email_subject')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="form-group">
    <label for="email_body">Email Body</label>
    <textarea type="text" name="email_body" class="form-control @error('email_body') is-invalid @enderror"
           id="email_body"
           placeholder="Email Body">{{ old('email_body', $job->email_body ?? '') }}</textarea>
    @error('email_body')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="frequency">Frequency</label>
        <select class="form-control @error('frequency') is-invalid @enderror" name="frequency" id="frequency" required>
            <option value="daily" {{ isset($job) && $job->frequency === 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ isset($job) && $job->frequency === 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ isset($job) && $job->frequency === 'monthly' ? 'selected' : '' }}>Monthly</option>
        </select>
        @error('frequency')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="execution_time">Execution Time</label>
        <input type="time" name="execution_time" class="form-control @error('execution_time') is-invalid @enderror" id="execution_time"
            placeholder="Execution Time" value="{{ old('execution_time', date('H:i', strtotime($job->execution_time ?? ''))) }}" required>
        @error('execution_time')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="is_active">Active</label>
    <input type="checkbox" name="is_active" id='is_active' {{ isset($job) && $job->is_active ? 'checked' : '' }}
        class="@error('is_active') is-invalid @enderror">
    @error('is_active')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>


<button class="btn btn-primary" type="submit">Save</button>
<a class="btn btn-default" href="{{ route('jobs.index') }}">Cancel</a>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('email_body');
</script>
