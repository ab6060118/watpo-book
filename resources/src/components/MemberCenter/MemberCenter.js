import { Grid } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';
import {
  Route, Switch, NavLink, Redirect,
} from 'react-router-dom';
import CheckOrders from '../CheckOrders';
import Coupons from '../Coupons/Coupons';
import Orders from '../Orders/Orders';
import Points from '../Points/Points';

export default () => {
  const { t } = useTranslation();
  return (
    <Grid>
      <div style={{ height: 70 }} />
      <div>
        <NavLink to="/memberCenter/checkOrders">
          <span>{t('checkOrCancelReservation')}</span>
        </NavLink>
        <NavLink to="/memberCenter/orders" style={{ marginLeft: 16 }}>
          <span>{t('myOrders')}</span>
        </NavLink>
        <NavLink to="/memberCenter/coupons" style={{ marginLeft: 16 }}>
          <span>{t('couponList')}</span>
        </NavLink>
        <NavLink to="/memberCenter/points" style={{ marginLeft: 16 }}>
          <span>{t('myPoint')}</span>
        </NavLink>
      </div>
      <Switch>
        <Route path="/memberCenter/checkOrders">
          <Orders status={1} />
        </Route>
        <Route path="/memberCenter/orders">
          <Orders status={5} />
        </Route>
        <Route path="/memberCenter/coupons" component={Coupons} />
        <Route path="/memberCenter/points" component={Points} />
        <Redirect to="/memberCenter/checkOrders" />
      </Switch>
    </Grid>
  );
};
