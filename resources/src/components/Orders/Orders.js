import { useEffect, useState } from 'react';
import { find } from 'lodash';
import { Col, Row } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { withRouter } from 'react-router-dom';
import Link from 'react-router-dom/Link';
import SweetAlert from 'sweetalert-react/lib/SweetAlert';
import { renderToStaticMarkup } from 'react-dom/server';
import LoadingAnimation from '../LoadingAnimation';
import Item from './Item';
import Alert from '../Reservation/Alert';
import DrawImage from '../../assets/images/get-coupon.gif';

export default withRouter(({ location, history, status } = {}) => {
  const [loading, setLoading] = useState(false);
  const [isDrawing, setIsDrawing] = useState(false);
  const [isOrderDetailShowed, setIsOrderDetailShowed] = useState(false);
  const [orderToShow, setOrderToShow] = useState({});
  const [orders, setOrders] = useState([]);
  const [alert, setAlert] = useState({
    showAlert: false,
    alertTitle: '',
    alertText: '',
  });
  const { contactNumber, name } = useSelector((state) => state.checkOrdersInfo) || {};
  const { t } = useTranslation();
  const { showAlert, alertText, alertTitle } = alert || {};

  const getOrders = () => {
    const csrfToken = document.querySelector('input[name="_token"]').value;
    setLoading(true);

    axios({
      method: 'get',
      url: '/api/order/list',
      params: {
        name,
        phone: contactNumber,
      },
      headers: { 'X-CSRF-TOKEN': csrfToken },
      responseType: 'json',
    })
      .then((response) => {
        if (response.statusText === 'OK') {
          setOrders(response.data.map((order) => ({
            ...order,
            status,
          })));
          setLoading(false);
        }
      })
      .catch((error) => {
        console.log(error);
        setLoading(false);
        setAlert({
          showAlert: true,
          alertTitle: 'Error',
          alertText: 'errorHint_system',
        });
      });
  };

  useEffect(() => {
    if (name && contactNumber) {
      getOrders();
    }
  }, [name, contactNumber, status]);

  const onDrawButtonClick = (id) => {
    const csrf_token = document.querySelector('input[name="_token"]').value;

    setIsDrawing(true);

    Promise.all([
      new Promise((res) => {
        setTimeout(() => {
          res();
        }, 1500);
      }),
      axios({
        method: 'post',
        url: '/api/coupon/draw',
        params: {
          name,
          phone: contactNumber,
          orderId: id,
        },
        headers: { 'X-CSRF-TOKEN': csrf_token },
        responseType: 'json',
      })
        .then((response) => {
          if (response.statusText !== 'OK') {
            throw new Error('抽獎失敗');
          }
        }),
    ])
      .then(() => {
        setIsDrawing(false);
      })
      .catch((error) => {
        console.log(error);
        setAlert({
          showAlert: true,
          alertTitle: 'Error',
          alertText: 'errorHint_system',
        });
        setIsDrawing(false);
      });
  };

  const onDetailButtonClick = (id) => {
    const order = find(orders || [], ['id', id]);
    setIsOrderDetailShowed(true);
    setOrderToShow(<Alert
      notice={t('reserveNotice2')}
      text={
        `${t('locations')}: ${order.shop}\n${
          t('registrationNumber')}: ${order.shop === 1 ? '02 2581-3338' : '02 2570-9393'}\n${
          t('contactNumber')}: ${contactNumber}\n${
          t('reservatorDate')}: ${order.start_time}\n`
        // + "服務: " + serviceName + "\n"
          + `包廂:${order.service
          }人數: ${order.person} ${order.person > 1 ? t('people') : t('person')}\n${
            t('reserveNotice1')}\n${
            t('reserveNotice2')}\n${
            t('reserveNotice3')}\n` + '本店不可攜帶寵物'
      }
    />);
  };

  const onCancelButtonClick = (id) => {
    setAlert({
      showAlert: true,
      alertTitle: 'concelConfirm',
      alertText: 'orderCanceledConfirm',
      id,
    });
  };

  return (
    <>
      <div className="reservationContainer" style={{ marginTop: 16 }}>
        <Row>
          <Col md={12}>
            <p className="prevStap">
              <Link to={{
                pathname: '/auth',
                state: {
                  from: location.pathname,
                },
              }}
              >
                <span>
                  <i className="fa fa-angle-left" aria-hidden="true" />
                  {` ${t('searchAgain')}`}
                </span>
              </Link>
            </p>
          </Col>
          <Col md={12} style={{ marginTop: 16 }}>
            {orders.length === 0 && t('noOrder')}
            {orders.map((order) => {
              const { id } = order || {};
              return (
                <Item
                  key={id}
                  data={order}
                  onDetailButtonClick={onDetailButtonClick}
                  onDrawButtonClick={onDrawButtonClick}
                  onCancelButtonClick={onCancelButtonClick}
                />
              );
            })}
          </Col>
          <div className="checkOrdersContent" style={{ padding: '16px 0' }} />
          {loading && !isDrawing && <Col md={12}><LoadingAnimation /></Col>}
          {isDrawing && (
            <div className="dotContainer" style={{ width: 'auto', height: 'auto' }}>
              <img src={DrawImage} alt="" />
            </div>
          )}
        </Row>
      </div>
      <SweetAlert
        show={isOrderDetailShowed}
        title={t('reserveDetail')}
        html
        text={isOrderDetailShowed ? (renderToStaticMarkup(orderToShow)) : ''}
        onConfirm={() => {
          setIsOrderDetailShowed(false);
        }}
      />
      <SweetAlert
        show={showAlert}
        showCancelButton={alertTitle === 'concelConfirm'}
        cancelButtonText="No"
        confirmButtonText="Yes"
        title={t(alertTitle)}
        text={t(alertText)}
        onCancel={() => setAlert({ showAlert: false })}
        onConfirm={() => {
          setAlert({
            ...alert,
            showAlert: false,
          });
          if (alertTitle === 'concelConfirm') {
            const csrf_token = document.querySelector('input[name="_token"]').value;

            setLoading(true);

            axios({
              method: 'post',
              url: '/api/order/customer/cancel',
              params: {
                name,
                phone: contactNumber,
                order_id: alert.id,
              },
              headers: { 'X-CSRF-TOKEN': csrf_token },
              responseType: 'json',
            })
              .then((response) => {
                if (response.statusText === 'OK') {
                  setLoading(false);
                  setAlert({
                    showAlert: true,
                    alertTitle: 'success',
                    alertText: 'orderCanceled',
                  });
                  getOrders();
                }
              })
              .catch((error) => {
                console.log(error);
                setLoading(false);
                setAlert({
                  showAlert: true,
                  alertTitle: 'Error',
                  alertText: 'errorHint_system',
                });
              });
          }
          if (alertTitle === 'Error') {
            history.push('/auth');
          }
        }}
      />
    </>
  );
});
