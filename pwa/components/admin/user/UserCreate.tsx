import PageShow from "../page/PageShow";
import {CreateGuesser, FieldGuesser} from "@api-platform/admin";

const UserCreate = (props:{props:any}) => (
  <CreateGuesser {...props}>
    <FieldGuesser source={"email"} />
    <FieldGuesser source={"roles"} />
    <FieldGuesser source={"password"} />
    <FieldGuesser source={"userIdentifier"} />
    <FieldGuesser source={"defaultStatus"} />
    <FieldGuesser source={"status"} />
  </CreateGuesser>
);

export default UserCreate;
