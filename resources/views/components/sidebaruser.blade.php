
<div class="col-md-3 mb-4">
    <div class="list-group shadow-sm">
        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action active">
            <i class="fas fa-user me-2"></i> ข้อมูลผู้ใช้งาน
        </a>
        <a href="{{ route('user.address.index') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-map-marker-alt me-2"></i> ที่อยู่ของฉัน
        </a>
        <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-box me-2"></i> รายการสั่งซื้อ
        </a>
        <a href="{{ route('user.points') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-coins me-2"></i> คะแนนสะสม
        </a>
    </div>
</div>
