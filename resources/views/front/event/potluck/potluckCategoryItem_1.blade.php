<div class="accordion accordion-flush" id="accordioncatList">
    <div class="accordion-item red-border">
      <h2 class="accordion-header" id="lumpia">
        <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#lumpia-collapseOne" aria-expanded="false" aria-controls="lumpia-collapseOne">
            <div class="d-flex align-items-center">
                <span class="me-1 list-sub-head">0</span>
                <div>
                    <h5>{{$itemdata['item']}}</h5>
                    <p>Requested by: {{$user}}</p>
                </div>
            </div>
            <div class="ms-auto">
                <span class="me-3">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71"/>
                    </svg>
                </span>
            </div>
            <div class="ms-auto">
                <h6>0/{{$itemdata['quantity']}}</h6>
            </div>
        </button>
      </h2>
      <div id="lumpia-collapseOne" class="accordion-collapse collapse" aria-labelledby="lumpia" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">
            <div class="accordion-body-content" style="display: none;">
                <img src="./assets/image/user-img.svg" alt="">
                <h5>Emilia Clark</h5>
                <span class="ms-auto">1</span>
            </div>
            <div class="accordion-body-content limits-count">
                <img src="./assets/image/user-img.svg" alt="">
                <h5>Pristia Candra</h5>
                <div class="qty-container ms-auto">
                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                    <input type="number" name="qty" value="0" class="input-qty"/>
                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                </div>
                <div class="d-flex">
                    <a href="#" class="me-3"> 
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="#">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>