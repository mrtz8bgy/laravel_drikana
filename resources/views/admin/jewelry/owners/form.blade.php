<div class="mb-3">
    <label class="form-label">نام کامل</label>
    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
           value="{{ old('full_name', $owner->full_name ?? '') }}">
    @error('full_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">کد ملی</label>
    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror"
           maxlength="10"
           value="{{ old('national_id', $owner->national_id ?? '') }}">
    @error('national_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">تلفن</label>
    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
           maxlength="14"
           value="{{ old('phone', $owner->phone ?? '') }}">
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">ایمیل</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $owner->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">آدرس</label>
    <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $owner->address ?? '') }}</textarea>
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($owner) ? 'بروزرسانی مالک' : 'ثبت مالک جدید' }}
</button>

<a href="{{ route('admin.jewelry.owners.index') }}" class="btn btn-secondary">بازگشت</a>
