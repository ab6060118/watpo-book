import { useSelector } from 'react-redux';
import { Redirect, Route } from 'react-router-dom';

export default ({ component: Component, ...rest }) => {
  const { contactNumber, name } = useSelector((state) => state.checkOrdersInfo) || {};

  return (
    <Route
      {...rest}
      render={(props) => {
        const { location } = props || {};

        if (contactNumber && name) return <Component {...props} />;

        return (
          <Redirect
            to={{
              pathname: '/auth',
              state: { from: location.pathname },
            }}
          />
        );
      }}
    />
  );
};
