<section class="collection-wrapper">
        <div class="container">
            <div class="content">
                <h2>Find the Perfect <br> Design in Our Collection</h2>
                <p>Customizable Designs to Reflect Your Unique Event</p>
            </div>
            {{-- {{$getDesignData}} --}}
            <div class="filter-main-wrp">
                <div class="filters-drp">
                    <h5>Filter By</h5>
                    <div class="filter-dropdowns">
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                Categories
                            </button>
                            <div class="dropdown-menu collection-menu">
                                <div class="filter-head">
                                    <h5>Categories</h5>
                                    <a href="#" class="reset-btn" id="resetCategories">Reset</a>
                                </div>
                                <div class="filter-categories">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-check-label" for="Allcat">All Categories</label>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s"
                                            id="Allcat">
                                    </div>
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($categories as $category)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading{{ $category->id }}">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $category->id }}"aria-expanded="true"
                                                        aria-controls="collapse{{ $category->id }}">
                                                        {{ $category->category_name }}
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $category->id }}"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="heading{{ $category->id }}"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul>
                                                            @foreach ($category->subcategory as $subcategory)
                                                                <li>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between">
                                                                        <label class="form-check-label"
                                                                            for="allbirth">{{ $subcategory->subcategory_name }}</label>
                                                                        <input class="form-check-input"
                                                                            type="checkbox"
                                                                            id="subcategory{{ $subcategory->id }}"
                                                                            data-category-id="{{ $category->id }}"
                                                                            data-subcategory-id="{{ $subcategory->id }}">
                                                                    </div>
                                                                </li>
                                                            @endforeach

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                Features
                            </button>
                            <div class="dropdown-menu collection-menu">
                                <div class="filter-head">
                                    <h5>Categories</h5>
                                    <a href="#" class="reset-btn">Reset</a>
                                </div>
                                <div class="filter-categories">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-check-label" for="Allcat">All Categories</label>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s"
                                            checked="" id="Allcat">
                                    </div>
                                    <div class="accordion" id="accordionExample1">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne1">
                                                <button class="accordion-button" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne1"
                                                    aria-expanded="true" aria-controls="collapseOne1">
                                                    Holidays
                                                </button>
                                            </h2>
                                            <div id="collapseOne1" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne1" data-bs-parent="#accordionExample1">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo2">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo2"
                                                    aria-expanded="false" aria-controls="collapseTwo2">
                                                    Wedding
                                                </button>
                                            </h2>
                                            <div id="collapseTwo2" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo2" data-bs-parent="#accordionExample1">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth1">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth1"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth1">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth1"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth1">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth1"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth1">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth1"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree3">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree3"
                                                    aria-expanded="false" aria-controls="collapseThree3">
                                                    Birthdays
                                                </button>
                                            </h2>
                                            <div id="collapseThree3" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree3" data-bs-parent="#accordionExample1">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth2">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth2"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth2">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth2"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth2">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth2"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth2">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth2"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                Themes
                            </button>
                            <div class="dropdown-menu collection-menu">
                                <div class="filter-head">
                                    <h5>Categories</h5>
                                    <a href="#" class="reset-btn">Reset</a>
                                </div>
                                <div class="filter-categories">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-check-label" for="Allcat">All Categories</label>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s"
                                            checked="" id="Allcat">
                                    </div>
                                    <div class="accordion" id="accordionExample2">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne1">
                                                <button class="accordion-button" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne1"
                                                    aria-expanded="true" aria-controls="collapseOne1">
                                                    Holidays
                                                </button>
                                            </h2>
                                            <div id="collapseOne1" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne1" data-bs-parent="#accordionExample2">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo2">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo2"
                                                    aria-expanded="false" aria-controls="collapseTwo2">
                                                    Wedding
                                                </button>
                                            </h2>
                                            <div id="collapseTwo2" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo2" data-bs-parent="#accordionExample2">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth1">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth1"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth1">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth1"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth1">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth1"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth1">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth1"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree3">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree3"
                                                    aria-expanded="false" aria-controls="collapseThree3">
                                                    Birthdays
                                                </button>
                                            </h2>
                                            <div id="collapseThree3" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree3" data-bs-parent="#accordionExample2">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth2">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth2"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth2">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth2"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth2">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth2"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth2">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth2"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                Color
                            </button>
                            <div class="dropdown-menu collection-menu">
                                <div class="filter-head">
                                    <h5>Categories</h5>
                                    <a href="#" class="reset-btn">Reset</a>
                                </div>
                                <div class="filter-categories">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-check-label" for="Allcat">All Categories</label>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s"
                                            checked="" id="Allcat">
                                    </div>
                                    <div class="accordion" id="accordionExample3">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne1">
                                                <button class="accordion-button" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne1"
                                                    aria-expanded="true" aria-controls="collapseOne1">
                                                    Holidays
                                                </button>
                                            </h2>
                                            <div id="collapseOne1" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne1" data-bs-parent="#accordionExample3">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo2">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo2"
                                                    aria-expanded="false" aria-controls="collapseTwo2">
                                                    Wedding
                                                </button>
                                            </h2>
                                            <div id="collapseTwo2" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo2" data-bs-parent="#accordionExample3">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth1">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth1"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth1">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth1"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth1">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth1"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth1">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth1"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree3">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree3"
                                                    aria-expanded="false" aria-controls="collapseThree3">
                                                    Birthdays
                                                </button>
                                            </h2>
                                            <div id="collapseThree3" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree3" data-bs-parent="#accordionExample3">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="allbirth2">All
                                                                    Birthdays Kids Birthday</label>
                                                                <input class="form-check-input" id="allbirth2"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="babybirth2">Baby
                                                                    Birthday </label>
                                                                <input class="form-check-input" id="babybirth2"
                                                                    type="checkbox" name="Guest RSVP’s"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label" for="manbirth2">Mans
                                                                    Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="manbirth2"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <label class="form-check-label"
                                                                    for="womanbirth2">Womans Birthday</label>
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="Guest RSVP’s" id="womanbirth2"
                                                                    checked="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="total-items ms-auto">55 Items</h5>
            </div>
            {{-- {{ dd($categories);}} --}}
            <div class="row ">
                @foreach ($categories as $category)
                    @foreach ($category->subcategory as $subcategory)
                        @foreach ($subcategory->textdatas as $image)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item"
                                data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                                data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subcategory->id }}">
                                <a href="#" class="collection-card card-blue">
                                    <div class="card-img">
                                        <img src="{{ asset('uploads/images/' . $image->filled_image) }}"
                                            alt="shower-card">
                                    </div>
                                    <h4>{{ $category->category_name }}</h4>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                @endforeach
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-green">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card2.png') }}" alt="kids-card">
                        </div>
                        <h4>Kids Birthdays</h4>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-red">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card3.png') }}" alt="collect-card">
                        </div>
                        <h4>Simple Designs</h4>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-green">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card4.png') }}" alt="collect-card">
                        </div>
                        <h4>Floral Delight</h4>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="8s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-yellow">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card5.png') }}" alt="collect-card">
                        </div>
                        <h4>Weddings</h4>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="8s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-red">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card6.png') }}" alt="collect-card">
                        </div>
                        <h4>Baby Announcements</h4>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="8s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-green">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card7.png') }}" alt="collect-card">
                        </div>
                        <h4>Minimalist Bliss</h4>
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown"
                    data-wow-duration="8s" data-wow-delay="0" data-wow-offset="0">
                    <a href="#" class="collection-card card-blue">
                        <div class="card-img">
                            <img src="{{ asset('assets/front/image/collect-card8.png') }}" alt="collect-card">
                        </div>
                        <h4>Vintage Romance</h4>
                    </a>
                </div>
            </div>
        </div>
    </section>