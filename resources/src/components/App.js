// 負責渲染和清理資料

import { Switch, Route, BrowserRouter } from 'react-router-dom';
import Nav from './Nav';
import Landpage from './Landpage';
import Reservation from './Reservation';
import MemberCenter from './MemberCenter/MemberCenter';
import Auth from './Auth';
import PrivateRoute from './PrivateRoute';

class App extends React.Component {
  render() {
    return (
      <BrowserRouter>
        <div>
          <input name="_token" value={123} hidden />
          <Nav />
          <Switch>
            <Route exact path="/" component={Landpage} />
            <Route path="/reservation/:step" component={Reservation} />
            <PrivateRoute path="/memberCenter" component={MemberCenter} />
            <Route path="/auth" component={Auth} />
          </Switch>
        </div>
      </BrowserRouter>
    );
  }
}

export default App;
