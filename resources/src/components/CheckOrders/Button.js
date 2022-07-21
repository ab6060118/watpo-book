import { withTranslation } from 'react-i18next';

const { Col } = ReactBootstrap;
const { Button } = ReactBootstrap;

function Button_(props) {
  const { t } = props;
  return (
    <Col md={12}>
      <Button
        bsStyle="primary"
        bsSize="large"
        type="submit"
        disabled={props.disabled}
        onClick={props.disabled ? null : props.clickHandle}
      >
        {t('nextStep')}
      </Button>
    </Col>
  );
}

export default withTranslation()(Button_);
