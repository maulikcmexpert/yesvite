<div class="trgistry-content d-flex justify-content-between" id="registry{{$registry_item}}">
    <div class="d-flex align-items-start">
        {{-- <input type="checkbox" id="test" name="gift_registry_check"> --}}
        <input class="form-check-input" type="checkbox" name="gift_registry[]" data-item="{{$recipient_name}}" data-registry="{{$registry_link}}">
        <div class="ms-1">
            <h5 id="added_recipient_name">{{$recipient_name}}</h4>
            <a href="#" id="added_registry_link">{{$registry_link}}</a>
        </div>
    </div>
    <div>
        <div class="d-flex ms-auto">
            <a href="#" data-id="{{$registry_item}}" class="me-3 edit_gift_registry"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.1665 1.66699H7.49984C3.33317 1.66699 1.6665 3.33366 1.6665 7.50033V12.5003C1.6665 16.667 3.33317 18.3337 7.49984 18.3337H12.4998C16.6665 18.3337 18.3332 16.667 18.3332 12.5003V10.8337" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.3666 2.51639L6.7999 9.08306C6.5499 9.33306 6.2999 9.82472 6.2499 10.1831L5.89157 12.6914C5.75823 13.5997 6.3999 14.2331 7.30823 14.1081L9.81657 13.7497C10.1666 13.6997 10.6582 13.4497 10.9166 13.1997L17.4832 6.63306C18.6166 5.49972 19.1499 4.18306 17.4832 2.51639C15.8166 0.849722 14.4999 1.38306 13.3666 2.51639Z" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12.4248 3.45801C12.9831 5.44967 14.5415 7.00801 16.5415 7.57467" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a >
            <a href="#" class="delete_gift_registry" data-id="{{$registry_item}}">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.0835 4.14199L7.26683 3.05033C7.40016 2.25866 7.50016 1.66699 8.9085 1.66699H11.0918C12.5002 1.66699 12.6085 2.29199 12.7335 3.05866L12.9168 4.14199" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.7082 7.61621L15.1665 16.0079C15.0748 17.3162 14.9998 18.3329 12.6748 18.3329H7.32484C4.99984 18.3329 4.92484 17.3162 4.83317 16.0079L4.2915 7.61621" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.6084 13.75H11.3834" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.9165 10.417H12.0832" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a >
        </div>
    </div>
</div>