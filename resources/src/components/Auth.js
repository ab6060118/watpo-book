import { Grid } from 'react-bootstrap';
import { withRouter } from 'react-router-dom';
import InputInfo from './CheckOrders/InputInfo';

export default withRouter(({ location, history }) => {
  const { state: { from } = {} } = location || {};

  const nextStep = () => {
    history.push(from || '/memberCenter/checkOrders');
  };

  return (
    <Grid>
      <div style={{ height: 70 }} />
      <InputInfo nextStep={nextStep} />
    </Grid>
  );
});
