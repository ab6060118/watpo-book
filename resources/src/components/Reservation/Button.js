import { withTranslation } from 'react-i18next';

const { Col } = ReactBootstrap;
const { Button } = ReactBootstrap;

function Button_(props) {
  const { t } = props;
  return (
    <Col md={12}>
      <Button type="submit" bsStyle="primary" bsSize="large" disabled={props.disabled} onClick={props.disabled ? null : props.clickHandle}>
        {props.currentStep == 2 ? t('send') : t('nextStep')}
      </Button>
    </Col>
  );
}

export default withTranslation()(Button_);
