import Home from './components/Home.vue';
import Search from './components/Search.vue';
import ProductDetails from './components/ProductDetails.vue';
import Login from './components/Auth/Login.vue';
import Register from './components/Auth/Register.vue';
import ForgotPassword from './components/Auth/ForgotPassword.vue';
import Dashboard from './components/User/Dashboard.vue';
import Orders from './components/User/Orders.vue';
import Promotion from './components/User/Promotion.vue';
import Redenvelope from './components/User/Redenvelope.vue';
import AddRedenvelope from './components/User/AddRedenvelope.vue';
import Recharge from './components/User/Recharge.vue';
import RechargeRecord from './components/User/RechargeRecord.vue';
import Withdrawal from './components/User/Withdrawal.vue';
import WithdrawalRecord from './components/User/WithdrawalRecord.vue';
import Transactions from './components/User/Transactions.vue';
// Bank Card
import Bankcard from './components/User/Bankcard.vue';
import AddBankCard from './components/User/AddBankCard.vue';
import EditBankcard from './components/User/EditBankcard.vue';
// Address
import Address from './components/User/Address.vue';
import AddAddress from './components/User/AddAddress.vue';
import EditAddress from './components/User/EditAddress.vue';
import ResetPassword from './components/User/ResetPassword.vue';
import Complaints from './components/User/Complaints.vue';
import AddComplaints from './components/User/AddComplaints.vue';
import PrivacyPolicy from './components/User/PrivacyPolicy.vue';
import Riskagreement from './components/User/Riskagreement.vue';
import Reward from './components/User/Reward.vue';
import Interest from './components/User/Interest.vue';
// ALL GAMES
import Game from './components/Game.vue';
import Coin from './components/Coin.vue';
import AndarBahar from './components/AndarBahar.vue';
import Spin from './components/Spin.vue';
// Payment
import Payment from './components/User/Payment.vue';


// ADMIN ROUTES
var admin_prefix = "/admin";
import AdminDashboard from './components/Admin/Dashboard.vue';
import Upi from './components/Admin/Upi.vue';
import AddUpi from './components/Admin/AddUpi.vue';
import Users from './components/Admin/Users.vue';
import Banner from './components/Admin/Banner.vue';
import DepositRequest from './components/Admin/DepositRequest.vue';
import WithdrawalRequest from './components/Admin/WithdrawalRequest.vue';
import RewardWithdrawal from './components/Admin/RewardWithdrawal.vue';
import GameMode from './components/Admin/GameMode.vue';
import Settings from './components/Admin/Settings.vue';
import UserDetails from './components/Admin/UserDetails.vue';
import DummyWins from './components/Admin/DummyWins.vue';
import Complains from './components/Admin/Complains.vue';
import Sports from './components/Admin/Sports.vue';
import Matchs from './components/Admin/Matchs.vue';
import Markets from './components/Admin/Markets.vue';

export const routes = [
    { path: '/home', component: Home, name: 'Home' },
    { path: '/search', component: Search, name: 'Search' },
    { path: '/product/details', component: ProductDetails, name: 'ProductDetails' },
    { path: '/login', component: Login, name: 'Login' },
    { path: '/register', component: Register, name: 'Register' },
    { path: '/forgot-password', component: ForgotPassword, name: 'ForgotPassword' },
    { path: '/dashboard', component: Dashboard, name: 'Dashboard' },
    { path: '/orders', component: Orders, name: 'Orders' },
    { path: '/promotion', component: Promotion, name: 'Promotion' },
    { path: '/redenvelope', component: Redenvelope, name: 'Redenvelope' },
    { path: '/add/redenvelope', component: AddRedenvelope, name: 'AddRedenvelope' },
    { path: '/recharge', component: Recharge, name: 'Recharge' },
    { path: '/recharge/record', component: RechargeRecord, name: 'RechargeRecord' },
    { path: '/withdrawal', component: Withdrawal, name: 'Withdrawal' },
    { path: '/withdrawal/record', component: WithdrawalRecord, name: 'WithdrawalRecord' },
    { path: '/transactions', component: Transactions, name: 'Transactions' },
    // Bank Card
    { path: '/bankcard', component: Bankcard, name: 'Bankcard' },
    { path: '/add/bank/card', component: AddBankCard, name: 'AddBankCard' },
    { path: '/edit/bank/card/:id/edit', component: EditBankcard, name: 'edit_bank_card', props:true },
    // Address
    { path: '/address', component: Address, name: 'Address' },
    { path: '/add/address', component: AddAddress, name: 'AddAddress' },
    { path: '/edit/address/card/:id/edit', component: EditAddress, name: 'edit_address', props:true },
    // Other
    { path: '/reset/password', component: ResetPassword, name: 'ResetPassword' },
    { path: '/complaints', component: Complaints, name: 'Complaints' },
    { path: '/add/complaints', component: AddComplaints, name: 'AddComplaints' },
    { path: '/privacy/policy', component: PrivacyPolicy, name: 'PrivacyPolicy' },
    { path: '/risk/agreement', component: Riskagreement, name: 'Riskagreement' },
    { path: '/reward', component: Reward, name: 'Reward' },
    { path: '/interest', component: Interest, name: 'Interest' },
    { path: '/payment', component: Payment, name: 'Payment' },
    // ALL GAMES
    { path: '/game/win', component: Game, name: 'Game' },
    { path: '/game/coin', component: Coin, name: 'Coin' },
    { path: '/game/spin', component: Spin, name: 'Spin' },
    { path: '/game/andar-bahar', component: AndarBahar, name: 'AndarBahar' },
    //////////////// ADMIN ////////////////
    { path: admin_prefix + '/dashboard', component: AdminDashboard, name: 'AdminDashboard' },
    { path: admin_prefix + '/upi', component: Upi, name: 'Upi' },
    { path: admin_prefix + '/add/upi', component: AddUpi, name: 'AddUpi' },
    { path: admin_prefix + '/users', component: Users, name: 'Users' },
    { path: admin_prefix + '/banner', component: Banner, name: 'Banner' },
    { path: admin_prefix + '/deposite/request', component: DepositRequest, name: 'DepositRequest' },
    { path: admin_prefix + '/withdrawal/request', component: WithdrawalRequest, name: 'WithdrawalRequest' },
    { path: admin_prefix + '/reward/withdrawal', component: RewardWithdrawal, name: 'RewardWithdrawal' },
    { path: admin_prefix + '/game-mode', component: GameMode, name: 'GameMode' },
    { path: admin_prefix + '/settings', component: Settings, name: 'Settings' },
    { path: admin_prefix + '/user-details/:id', component: UserDetails, name: 'UserDetails', props: true },
    { path: admin_prefix + '/dummy/wins', component: DummyWins, name: 'DummyWins' },
    { path: admin_prefix + '/complains', component: Complains, name: 'Complains' },
    { path: admin_prefix + '/sports', component: Sports, name: 'Sports' },
    { path: admin_prefix + '/matchs', component: Matchs, name: 'Matchs' },
    { path: admin_prefix + '/markets', component: Markets, name: 'Markets' },

];


