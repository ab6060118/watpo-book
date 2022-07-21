import { useTranslation } from 'react-i18next';

const statusStringMap = {
  1: '已預約',
  5: '已成立',
};

export default ({
  data,
  onDetailButtonClick,
  onCancelButtonClick,
  onDrawButtonClick,
}) => {
  const { t } = useTranslation();
  const {
    id,
    status,
    person,
    service,
    service_provider,
    shop,
    start_time,
  } = data;

  return (
    <div className="order">
      <div className="coupon-content-container">
        <div>
          {t('orderId')}
          {id}
        </div>
        <div>
          {t('orderStatus')}
          {statusStringMap[status] || ''}
        </div>
        <div>
          {t('time')}
          ：
          {start_time}
        </div>
        <div>
          {t('branch')}
          ：
          {shop}
        </div>
        <div>
          {t('service')}
          ：
          {service}
        </div>
        <div>
          {t('guestNum')}
          ：
          {person}
        </div>
        <div>
          {t('operator')}
          ：
          {service_provider}
        </div>
      </div>
      <div className="order-action-container">
        <button
          type="button"
          onClick={() => onDetailButtonClick(id)}
        >
          {t('checkOrderDetail')}
        </button>
        {status === 5
            && (
              <button
                type="button"
                style={{ marginLeft: 8 }}
                onClick={() => onDrawButtonClick(id)}
              >
                {t('drawCoupon')}
              </button>
            )}
        {
          status === 1
            && (
              <button
                type="button"
                style={{ marginLeft: 8 }}
                onClick={() => onCancelButtonClick(id)}
              >
                {t('cancel')}
              </button>
            )
        }
      </div>
    </div>
  );
};
