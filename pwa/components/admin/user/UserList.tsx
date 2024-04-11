import PageShow from "../page/PageShow";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";

const UserList = (props:{props:any}) => (
  <ListGuesser {...props}>
    <FieldGuesser source={"email"} />
    <FieldGuesser source={"roles"} />
    <FieldGuesser source={"userIdentifier"} />
    <FieldGuesser source={"defaultStatus"} />
    <FieldGuesser source={"status"} />
  </ListGuesser>
);

export default UserList;
