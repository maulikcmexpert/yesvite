
<div class="accordion-body-content limits-count">
    <img src="{{ $profile }}" alt="profile">
    <h5>{{ $first_name }} {{$last_name }}</h5>
    <div class="qty-container qty-custom ms-auto">
        <button class="minus" data-category-id="{{ $category_id }}" data-item-id="{{ $item_id  }}" type="button">
            <i class="fa fa-minus"></i>
        </button>
        <input type="number" name="qty"    id="newQuantity_{{  $item_id  }}" value="{{ $quantity }}" class="input-qty itemQty" data-max="{{ $quantity }}" data-item-id="{{ $item_id  }}" />
        <button class="plus" data-category-id="{{ $category_id }}" data-item-id="{{ $item_id  }}" type="button">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="d-flex">
        <button type="button" class="saveItemBtn me-3 d-flex align-items-center justify-content-center edit-modal-btn" data-category-id="{{ $category_id }}" data-item-id="{{ $item_id  }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button type="button" class="delete-modal-btn deleteBtn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" data-category-id="{{ $category_id }}" data-item-id="{{ $item_id  }}">
                <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</div>
