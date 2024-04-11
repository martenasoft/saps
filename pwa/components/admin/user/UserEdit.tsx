import PageShow from "../page/PageShow";
import {EditGuesser, InputGuesser} from "@api-platform/admin";

const UserEdit = (props:{props:any}) => (
  <EditGuesser {...props}>
    <InputGuesser source={"email"} />
    <InputGuesser source={"roles"} />
    <InputGuesser source={"userIdentifier"} />
    <InputGuesser source={"defaultStatus"} />
    <InputGuesser source={"status"} />
  </EditGuesser>
);

export default UserEdit;
