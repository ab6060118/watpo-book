import { withTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';

const { Col } = ReactBootstrap;

function Steps(props) {
  const { t } = props;

  // set up steps
  const currentStep = +(props.step);
  const stepsData = [t('chooseStore'), t('chooseAmount'), t('checkDetails'), t('chooseTime')]; const pointer = { cursor: 'pointer' }; const
    currentStepStyle = { cursor: 'pointer', color: '#914327' };

  const steps = stepsData.map((step, index, arr) => {
    const divider = index < arr.length - 1 && (
    <span>
      {' '}
      <i className="fa fa-angle-right" aria-hidden="true" />
      {' '}
    </span>
    );
    if (currentStep > index) {
      return (
        <span key={index}>
          <Link to={`/reservation/${index}`}>
            <span
              style={currentStep === index ? currentStepStyle : null}
            >
              {step}
            </span>
          </Link>
          {divider}
        </span>
      );
    }
    return (
      <span key={index}>
        <span
          style={currentStep === index ? currentStepStyle : null}
        >
          {step}
        </span>
        {divider}
      </span>
    );
  });

  return (
    <Col md={12}>
      <div className="steps">
        {steps}
      </div>
    </Col>
  );
}

export default withTranslation()(Steps);
