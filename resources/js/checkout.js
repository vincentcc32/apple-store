function formatCurrency(number) {
  return number
    .toString()
    .replace(/\B(?=(\d{3})+(?!\d))/g, ',') + 'đ';
}

function parseCurrency(str) {
  return parseInt(str.replace(/[^\d]/g, ''), 10);
}

const provinces = document.querySelector('#provinces');
const ward = document.querySelector('#ward');
const totalFee = document.querySelector('#ship');
const timeShip = document.querySelector('#time_ship');
const totalPrice = document.querySelector('#total_price');
const shippingFee = document.getElementById('shipping_fee');
fetch(`${import.meta.env.VITE_API_GOSHIP}cities`)
  .then(res => res.json())
  .then((dataProvinces) => {
    let html = '';

    if (dataProvinces.code === 200 && dataProvinces.status === 'success') {
      dataProvinces.data.forEach(element => {
        html += `<option value="${element.name}" data-code="${element.id}">${element.name}</option>`;
      })

      provinces.innerHTML += html;

      // Bắt sự kiện onchange
      provinces.addEventListener('change', function () {
        const districtSelectedOption = this.options[this.selectedIndex];
        totalFee.innerHTML = 0 + 'đ';
        totalFee.dataset.total_fee = "0";
        totalPrice.innerHTML = formatCurrency(totalPrice.dataset.total_price - totalFee.dataset.total_fee);
        timeShip.innerHTML = '';
        if (districtSelectedOption.value !== '') {
          const provinceCode = districtSelectedOption.dataset.code;

          fetch(`${import.meta.env.VITE_API_GOSHIP}cities/${provinceCode}/districts`)
            .then(res => res.json())
            .then((dataWard) => {
              let html = '';

              if (dataWard.code === 200 && dataWard.status === 'success') {
                dataWard.data.forEach(element => {
                  html += `<option value="${element.name}" data-code="${element.id}">${element.name}</option>`;
                });
                ward.innerHTML = '<option value="">Chọn phường/xã...</option>';
                ward.innerHTML += html;



                ward.addEventListener('change', function () {
                  const wardSelectedOption = this.options[this.selectedIndex];
                  totalFee.innerHTML = 0 + 'đ';
                  timeShip.innerHTML = '';
                  totalFee.dataset.total_fee = "0";
                  totalPrice.innerHTML = totalPrice.dataset.total_price - totalFee.dataset.total_fee;
                  shippingFee.value = 0;
                  if (wardSelectedOption.value !== '') {
                    const districtCode = wardSelectedOption.dataset.code;
                    fetch(`${import.meta.env.VITE_API_GOSHIP}rates`,
                      {
                        method: 'POST',
                        headers: {
                          'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                          shipment: {
                            // city = da nang district = thanh khe 
                            address_from: { city: "550000", district: "550200" },
                            address_to: { city: provinceCode, district: districtCode },
                            parcel: { cod: 0, weight: 1000, length: 0, width: 0, height: 0 }
                          },
                        })
                      }
                    )
                      .then(res => res.json())
                      .then(feeData => {

                        if (feeData.status === 'success' && feeData.code === 200) {
                          totalFee.innerHTML = feeData.data.length > 0 ? formatCurrency(feeData.data[feeData.data.length - 1].total_fee) : 0 + 'đ';
                          timeShip.innerHTML = feeData.data.length > 0 ? feeData.data[feeData.data.length - 1].expected : '';
                          totalFee.dataset.total_fee = feeData.data.length > 0 ? feeData.data[feeData.data.length - 1].total_fee : 0;
                          totalPrice.innerHTML = formatCurrency(parseInt(totalPrice.dataset.total_price) + parseInt(totalFee.dataset.total_fee));
                          shippingFee.value = feeData.data.length > 0 ? feeData.data[feeData.data.length - 1].total_fee : 0;
                        } else {
                          totalFee.innerHTML = 0 + 'đ';
                          timeShip.innerHTML = '';
                          totalFee.dataset.total_fee = "0";
                          totalPrice.innerHTML = formatCurrency(totalPrice.dataset.total_price - totalFee.dataset.total_fee);
                          shippingFee.value = 0;
                        }

                      });
                  }
                });

              } else {
                ward.innerHTML = '<option value="">Chọn phường/xã...</option>';
                totalFee.innerHTML = 0 + 'đ';
                timeShip.innerHTML = '';
                totalFee.dataset.total_fee = "0";
                totalPrice.innerHTML = formatCurrency(totalPrice.dataset.total_price - totalFee.dataset.total_fee);
                shippingFee.value = 0;
              }

            });

        } else {
          ward.innerHTML = '<option value="">Chọn phường/xã...</option>';
          totalFee.innerHTML = 0 + 'đ';
          timeShip.innerHTML = '';
          totalFee.dataset.total_fee = "0";
          totalPrice.innerHTML = formatCurrency(totalPrice.dataset.total_price - totalFee.dataset.total_fee);
          shippingFee.value = 0;

        }



      });
    } else {
      provinces.innerHTML = '<option value="">Chọn tỉnh thành...</option>';
      totalFee.innerHTML = 0 + 'đ';
      timeShip.innerHTML = '';
      totalFee.dataset.total_fee = "0";
      totalPrice.innerHTML = formatCurrency(totalPrice.dataset.total_price - totalFee.dataset.total_fee);
      shippingFee.value = 0;
    }

  });



