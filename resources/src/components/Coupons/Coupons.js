import { useEffect, useState } from 'react';
import moment from 'moment';
import {
  Button, Col, Modal, Row,
} from 'react-bootstrap';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { withRouter } from 'react-router-dom';
import { QRCode } from 'react-qr-svg';
import Link from 'react-router-dom/Link';
import SweetAlert from 'sweetalert-react/lib/SweetAlert';
import LoadingAnimation from '../LoadingAnimation';
import Item from './Item';

const FILTER_TYPE_MAP = {
  VALID: 'VALID',
  EXPIRED: 'EXPIRED',
  USED: 'USED',
};

export default withRouter(({ location, history }) => {
  const [loading, setLoading] = useState(false);
  const [isShowQRCode, setIsShowQRCode] = useState(false);
  const [couponToShow, setCouponToShow] = useState({});
  const [filterType, setFilterType] = useState(FILTER_TYPE_MAP.VALID);
  const [coupons, setCoupons] = useState([]);
  const [alert, setAlert] = useState({
    showAlert: false,
    alertTitle: '',
    alertText: '',
  });
  const { contactNumber, name } = useSelector((state) => state.checkOrdersInfo) || {};
  const { t } = useTranslation();
  const { showAlert, alertText, alertTitle } = alert || {};

  useEffect(() => {
    if (name && contactNumber) {
      const csrfToken = document.querySelector('input[name="_token"]').value;
      setLoading(true);

      // 取得折價券列表
      axios({
        method: 'get',
        url: '/api/coupon/list',
        params: {
          name,
          phone: contactNumber,
        },
        headers: { 'X-CSRF-TOKEN': csrfToken },
        responseType: 'json',
      })
        .then((response) => {
          if (response.statusText === 'OK') {
            setCoupons(response.data.map((coupon) => {
              const { usedTime, expirationTime } = coupon || {};
              return {
                ...coupon,
                ...(usedTime && { usedTime: usedTime * 1000 }),
                ...(expirationTime && { expirationTime: expirationTime * 1000 }),
              };
            }));
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
    }
  }, [name, contactNumber]);

  const onItemClick = (coupon) => {
    setIsShowQRCode(true);
    setCouponToShow(coupon);
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
          <Col md={12}>
            <button
              className={[
                'toggle-button',
                ...(filterType === FILTER_TYPE_MAP.VALID ? ['active'] : []),
              ].join(' ')}
              type="button"
              onClick={() => { setFilterType(FILTER_TYPE_MAP.VALID); }}
            >
              {t('validCoupon')}

            </button>
            <button
              className={[
                'toggle-button',
                ...(filterType === FILTER_TYPE_MAP.EXPIRED ? ['active'] : []),
              ].join(' ')}
              type="button"
              onClick={() => { setFilterType(FILTER_TYPE_MAP.EXPIRED); }}
            >
              {t('expiredCoupon')}

            </button>
            <button
              className={[
                'toggle-button',
                ...(filterType === FILTER_TYPE_MAP.USED ? ['active'] : []),
              ].join(' ')}
              type="button"
              onClick={() => { setFilterType(FILTER_TYPE_MAP.USED); }}
            >
              {t('usedCoupon')}

            </button>
          </Col>
          <Col md={12} style={{ marginTop: 16 }}>
            {coupons.length === 0 && t('noCoupon')}
            {coupons
              .filter((coupon) => {
                const { isValid, usedTime, expirationTime } = coupon || {};
                if (filterType === FILTER_TYPE_MAP.EXPIRED) {
                  if (moment(expirationTime).isBefore(moment())) return true;
                }
                if (filterType === FILTER_TYPE_MAP.USED) {
                  if (usedTime) return true;
                }
                if (filterType === FILTER_TYPE_MAP.VALID) {
                  if (usedTime) return false;
                  if (isValid && moment(expirationTime).isAfter(moment())) return true;
                }
                return false;
              })
              .map((coupon) => {
                const { id } = coupon;
                return <Item data={coupon} onClick={onItemClick} key={id} />;
              })}
          </Col>
          <div className="checkOrdersContent" style={{ padding: '16px 0' }} />
          {loading && <Col md={12}><LoadingAnimation /></Col>}
        </Row>
      </div>
      <Modal show={isShowQRCode} onHide={() => setIsShowQRCode(false)}>
        <Modal.Header closeButton>
          <Modal.Title>{t('scanQRCode')}</Modal.Title>
        </Modal.Header>
        <Modal.Body className="qrcode-modal">
          <QRCode
            level="Q"
            style={{ width: 128 }}
            value={JSON.stringify({
              code: couponToShow.code,
              id: couponToShow.id,
              name: encodeURIComponent(name),
              phone: contactNumber,
              discount: couponToShow.discount,
            })}
          />
          <div style={{ marginTop: 16 }}>
            優惠碼：
            {couponToShow.code}
          </div>
          <Button onClick={() => { setIsShowQRCode(false); }}>{t('Close')}</Button>
        </Modal.Body>
      </Modal>
      <SweetAlert
        show={showAlert}
        title={t(alertTitle)}
        text={t(alertText)}
        onConfirm={() => {
          setAlert({
            ...alert,
            showAlert: false,
          });
          if (alertTitle === 'Error') {
            history.push('/auth');
          }
        }}
      />
    </>
  );
});
