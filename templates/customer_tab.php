 <div class="tab-pane fade" id="customer-tab">
  <form name="form-customer" method="post">
    <div class="row justify-content-between">
      <div class="col-12 col-md-4">
        <div class="form-group">
          <label class="form-label" for="customer-email">Email</label>
          <input class="form-control" type="email" id="customer-email" name="customer-email" placeholder="Введите email" required>
          <div class="form-validator"></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="form-group">
          <label class="form-label" for="customer-tel">Телефон</label>
          <input class="form-control" type="tel" id="customer-tel" name="customer-phone" placeholder="Введите телефон" required>
          <div class="form-validator"></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="form-group" id="customer-city-wrapper">
          <label class="form-label" for="customer-city">Город</label>
          <input class="form-control" id="customer-city-input" name="customer-city" placeholder="Введите город" required>
          <ul class="form-list d-none" id="customer-city-list">         
          </ul>
          <div class="form-validator"></div>
        </div>
      </div>
      <div class="col-12">
        <div class="form-group" id="customer-rubric-wrapper">
          <label class="form-label" for="customer-rubric">Рубрика</label>
          <ul class="form-choice" id="customer-rubric-choice"></ul>
           <input class="form-control" id="customer-rubric-input" placeholder="Введите рубрику">
          <ul class="form-list d-none" id="customer-rubric-list">       
          </ul>
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
    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
      <div class="country-block">
        <div class="count" id="belarus-customer-counter"></div>
        <div class="descr">Заказчиков из <span class="country">Беларуси</span></div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
      <div class="country-block">
        <div class="count" id="russia-customer-counter"></div>
        <div class="descr">Заказчиков из <span class="country">России</span></div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
      <div class="country-block">
        <div class="count" id="ukraine-customer-counter"></div>
        <div class="descr">Заказчиков из <span class="country">Украины</span></div>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
      <div class="country-block">
        <div class="count" id="kazakhstan-customer-counter"></div>
        <div class="descr">Заказчиков из <span class="country">Казахстана</span></div>
      </div>
    </div>
  </div>
</div>