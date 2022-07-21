import moment from 'moment';
import { useTranslation } from 'react-i18next';

export default ({ onClick, data }) => {
  const { t } = useTranslation();
  const {
    description,
    expirationTime,
    isValid,
    name,
    usedTime,
  } = data;

  return (
    <button
      className="coupon"
      type="button"
      disabled={!isValid || usedTime}
      onClick={() => { onClick(data); }}
    >
      {usedTime && <div className="tag">{t('used')}</div>}
      <div className="coupon-image-container" />
      <div className="coupon-content-container">
        <div>{name}</div>
        <div>{description}</div>
        <div style={{ flexGrow: 1, flexShrink: 1 }} />
        {usedTime
        && (
        <div>
          {t('usedDate')}
          {moment(usedTime.utcOffset(8).format('YYYY/MM/DD'))}
        </div>
        )}
        <div>
          {t('expirationDate')}
          {moment(expirationTime).utcOffset(8).format('YYYY/MM/DD')}
        </div>
      </div>
    </button>
  );
};
