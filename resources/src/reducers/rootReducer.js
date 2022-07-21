import { combineReducers } from 'redux';
import reducers from './reducers';

const rootReducer = combineReducers({
  lastAction: reducers.lastAction,
  loading: reducers.loading,
  checkOrdersInfo: reducers.checkOrdersInfo,
  phoneValidator: reducers.phoneValidator,
});

export default rootReducer;
