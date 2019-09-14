  <div class="tab-pane fade in active" id="doer-tab">
    <form name="form-doer" method="post">
      <div class="row justify-content-between">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label class="form-label" for="doer-email">Email</label>
            <input class="form-control"
            type="email" id="doer-email" name="doer-email" placeholder="Введите email" required>
            <div class="form-validator"></div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label class="form-label" for="doer-tel">Телефон</label>
            <input class="form-control" type="tel" id="doer-tel" name="doer-phone" placeholder="Введите телефон" required>
              <div class="form-validator"></div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group" id="doer-city-wrapper">
            <label class="form-label" for="doer-city">Город</label>
            <input class="form-control" type="text" id="doer-city-input" name="doer-city" placeholder="Введите город" required>
            <ul class="form-list" id="doer-city-list"></ul>
            <div class="form-validator"></div>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group" id="doer-rubric-wrapper">
            <label class="form-label" for="doer-rubric">Рубрика</label>
            <ul class="form-choice" id="doer-rubric-choice">
            </ul>
            <input class="form-control" id="doer-rubric-input" placeholder="Введите рубрику">
            <ul class="form-list" id="doer-rubric-list"></ul>
            <div class="form-validator"></div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-12 col-sm-6">
          <button class="btn d-block" type="submit">Зарегистрироваться</button>
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <div class="country-block">
          <div class="count" id="belarus-doer-counter"></div>
          <div class="descr">Исполнителей из <span class="country">Беларуси</span></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <div class="country-block">
          <div class="count" id="russia-doer-counter"></div>
          <div class="descr">Исполнителей из <span class="country">России</span></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <div class="country-block">
          <div class="count" id="ukraine-doer-counter"></div>
          <div class="descr">Исполнителей из <span class="country">Украины</span></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <div class="country-block">
          <div class="count" id="kazakhstan-doer-counter"></div>
          <div class="descr">Исполнителей из <span class="country">Казахстана</span></div>
        </div>
      </div>
    </div>
  </div>